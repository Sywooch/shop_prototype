<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\db\Transaction;
use yii\web\{UploadedFile,
    Response};
use yii\helpers\{Url,
    ArrayHelper};
use app\controllers\AbstractBaseController;
use app\helpers\{MappersHelper, 
    ModelsInstancesHelper, 
    PicturesHelper,
    CSVHelper,
    FiltersHelper};
use app\models\{ProductsModel, 
    CategoriesModel, 
    CommentsModel,
    CurrencyModel,
    SubcategoryModel,
    BrandsModel, 
    ColorsModel, 
    SizesModel,
    ProductsBrandsModel,
    ProductsColorsModel,
    ProductsSizesModel};

/**
 * Управляет административными функциями
 */
class AdminController extends AbstractBaseController
{
    /**
     * @var array конфиг для получения данных товаров из БД
     * @see self::actionShowProducts
     */
    private $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'queryClass'=>'app\queries\ProductsListAdminQueryCreator',
        'orderByField'=>'date',
        'getDataSorting'=>false,
    ];
    
    /**
     * Управляет отображением основной страницы входа
     */
    public function actionIndex()
    {
        try {
            $renderArray = array();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('index.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет продукт в БД
     * @return redirect
     */
    public function actionAddProducts()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_ADD_PRODUCT]);
            $brandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD_PRODUCT]);
            $colorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD_PRODUCT]);
            $sizesModel = new SizesModel(['scenario'=>SizesModel::GET_FOR_ADD_PRODUCT]);
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post()) && $brandsModel->load(\Yii::$app->request->post()) && $colorsModel->load(\Yii::$app->request->post()) && $sizesModel->load(\Yii::$app->request->post())) {
                $productsModel->imagesToLoad = UploadedFile::getInstances($productsModel, 'imagesToLoad');
                if ($productsModel->validate() && $brandsModel->validate() && $colorsModel->validate() && $sizesModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!PicturesHelper::createPictures($productsModel->imagesToLoad)) {
                            throw new ErrorException('Ошибка при обработке изображений!');
                        }
                        if(!$productsModel->upload()) {
                            throw new ErrorException('Ошибка при загрузке images!');
                        }
                        if(!PicturesHelper::createThumbnails(\Yii::getAlias('@pic/' . $productsModel->images))) {
                            throw new ErrorException('Ошибка при загрузке images!');
                        }
                        if (!MappersHelper::setProductsInsert($productsModel)) {
                            throw new ErrorException('Ошибка при сохранении продукта!');
                        }
                        if (!MappersHelper::setProductsBrandsInsert($productsModel, $brandsModel)) {
                            throw new ErrorException('Ошибка при сохранении ProductsBrandsModel!');
                        }
                        if (!MappersHelper::setProductsColorsInsert($productsModel, $colorsModel)) {
                            throw new ErrorException('Ошибка при сохранении ProductsColorsModel!');
                        }
                        if (!MappersHelper::setProductsSizesInsert($productsModel, $sizesModel)) {
                            throw new ErrorException('Ошибка при сохранении ProductsSizesModel!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        if (!empty($productsModel->images)) {
                            if (!PicturesHelper::deletePictures($productsModel->images)) {
                                throw new ErrorException('Ошибка при удалении изображений!');
                            }
                        }
                        throw $e;
                    }
                    
                    $transaction->commit();
                    
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$productsModel->categories, 'subcategory'=>$productsModel->subcategory, 'id'=>$productsModel->id]));
                }
            }
            
            $renderArray = array();
            $renderArray['productsModel'] = $productsModel;
            $renderArray['brandsModel'] = $brandsModel;
            $renderArray['colorsModel'] = $colorsModel;
            $renderArray['sizesModel'] = $sizesModel;
            $renderArray['brandsList'] = MappersHelper::getBrandsList(false);
            $renderArray['colorsList'] = MappersHelper::getColorsList(false);
            $renderArray['sizesList'] = MappersHelper::getSizesList(false);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('add-product.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет отображением товаров
     */
    public function actionShowProducts()
    {
        try {
            $productsModelFilter = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_ADMIN_FILTER]);
            
            if (!empty(\Yii::$app->filters->categories)) {
                $categoriesModel = MappersHelper::getCategoriesBySeocode(new CategoriesModel(['seocode'=>\Yii::$app->filters->categories]));
                \Yii::configure($productsModelFilter, ['id_categories'=>$categoriesModel->id]);
            }
            if (!empty(\Yii::$app->filters->subcategory)) {
                $subcategoryModel = MappersHelper::getSubcategoryBySeocode(new SubcategoryModel(['seocode'=>\Yii::$app->filters->subcategory]));
                \Yii::configure($productsModelFilter, ['id_subcategory'=>$subcategoryModel->id]);
            }
            
            if (empty(\Yii::$app->filters->getActive) && empty(\Yii::$app->filters->getNotActive)) {
                \Yii::$app->filters->getActive = true;
                \Yii::$app->filters->getNotActive = true;
            }
            
            $renderArray = array();
            $renderArray['productsModelFilter'] = $productsModelFilter;
            $renderArray['productsList'] = MappersHelper::getProductsList($this->_config);
            $renderArray['colorsList'] = MappersHelper::getColorsAdminList();
            $renderArray['sizesList'] = MappersHelper::getSizesAdminList();
            $renderArray['brandsList'] = MappersHelper::getBrandsAdminList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-products.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос к конкретному продукту
     * @return string
     */
    public function actionShowProductsDetail()
    {
        try {
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
            }
            if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                throw new ErrorException('Ошибка при получении ID продукта!');
            }
            
            $renderArray = array();
            if (empty($renderArray['productsModel'] = MappersHelper::getProductsById(new ProductsModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                return $this->redirect(URL::to(['admin/show-products']));
            }
            \Yii::configure($renderArray['productsModel'], ['scenario'=>ProductsModel::GET_FOR_UPDATE]);
            $renderArray['brandsModel'] = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD_PRODUCT, 'id'=>$renderArray['productsModel']->brands->id]);
            $renderArray['colorsModel'] = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD_PRODUCT, 'idArray'=>ArrayHelper::getColumn($renderArray['productsModel']->colors, 'id')]);
            $renderArray['sizesModel'] = new SizesModel(['scenario'=>SizesModel::GET_FOR_ADD_PRODUCT, 'idArray'=>ArrayHelper::getColumn($renderArray['productsModel']->sizes, 'id')]);
            $renderArray['colorsList'] = MappersHelper::getColorsList(false);
            $renderArray['sizesList'] = MappersHelper::getSizesList(false);
            $renderArray['brandsList'] = MappersHelper::getBrandsList(false);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-product-detail.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет cut данные товара в БД
     * @return redirect
     */
    public function actionUpdateProductsCut()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_UPDATE_CUT]);
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post())) {
                if ($productsModel->validate()) {
                    if ($productsModel->active != MappersHelper::getProductsById($productsModel)->active) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setProductsUpdate(['modelArray'=>[$productsModel], 'fields'=>['id', 'active']])) {
                                throw new ErrorException('Ошибка при обновлении данных!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                }
            }
            
            return $this->redirect(Url::to(['admin/show-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет full данные товара в БД
     * @return redirect
     */
    public function actionUpdateProducts()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_UPDATE]);
            $brandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD_PRODUCT]);
            $colorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD_PRODUCT]);
            $sizesModel = new SizesModel(['scenario'=>SizesModel::GET_FOR_ADD_PRODUCT]);
            
            $updateConfig = array();
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post()) && $brandsModel->load(\Yii::$app->request->post()) && $colorsModel->load(\Yii::$app->request->post()) && $sizesModel->load(\Yii::$app->request->post())) {
                if (!empty($productsModel->imagesToLoad)) {
                    $productsModel->imagesToLoad = UploadedFile::getInstances($productsModel, 'imagesToLoad');
                }
                if ($productsModel->validate() && $brandsModel->validate() && $colorsModel->validate() && $sizesModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!empty($productsModel->imagesToLoad)) {
                            if (!PicturesHelper::createPictures($productsModel->imagesToLoad)) {
                                throw new ErrorException('Ошибка при обработке изображений!');
                            }
                            if(!$productsModel->upload()) {
                                throw new ErrorException('Ошибка при загрузке images!');
                            }
                            if(!PicturesHelper::createThumbnails(\Yii::getAlias('@pic/' . $productsModel->images))) {
                                throw new ErrorException('Ошибка при загрузке images!');
                            }
                            $updateConfig['images'] = true;
                        }
                        $updateConfig['modelArray'][] = $productsModel;
                        
                        if (array_diff_assoc($productsModel->getDataArray(), MappersHelper::getProductsById($productsModel)->getDataArray())) {
                            if (!MappersHelper::setProductsUpdate($updateConfig)) {
                                throw new ErrorException('Ошибка при обновлении данных!');
                            }
                        }
                        
                        if ($brandsModel->id != $productsModel->brands->id) {
                            if (!MappersHelper::setProductsBrandsDelete([new ProductsBrandsModel(['id_products'=>$productsModel->id])])) {
                                throw new ErrorException('Ошибка при удалении записи products_brands!');
                            }
                            if (!MappersHelper::setProductsBrandsInsert($productsModel, $brandsModel)) {
                            throw new ErrorException('Ошибка при сохранении ProductsBrandsModel!');
                            }
                        }
                        
                        if (count($colorsModel->idArray) != count(ArrayHelper::getColumn($productsModel->colors, 'id')) || array_diff($colorsModel->idArray, ArrayHelper::getColumn($productsModel->colors, 'id'))) {
                            if (!MappersHelper::setProductsColorsDelete([new ProductsColorsModel(['id_products'=>$productsModel->id])])) {
                                throw new ErrorException('Ошибка при удалении записи products_colors!');
                            }
                            if (!MappersHelper::setProductsColorsInsert($productsModel, $colorsModel)) {
                                throw new ErrorException('Ошибка при сохранении ProductsColorsModel!');
                            }
                        }
                        
                        if (count($sizesModel->idArray) != count(ArrayHelper::getColumn($productsModel->colors, 'id')) || array_diff($sizesModel->idArray, ArrayHelper::getColumn($productsModel->colors, 'id'))) {
                            if (!MappersHelper::setProductsSizesDelete([new ProductsSizesModel(['id_products'=>$productsModel->id])])) {
                                throw new ErrorException('Ошибка при удалении записи products_sizes!');
                            }
                            if (!MappersHelper::setProductsSizesInsert($productsModel, $sizesModel)) {
                                throw new ErrorException('Ошибка при сохранении ProductsSizesModel!');
                            }
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        if (!empty($productsModel->imagesToLoad && !empty($productsModel->images))) {
                            if (!PicturesHelper::deletePictures($productsModel->images)) {
                                throw new ErrorException('Ошибка при удалении изображений!');
                            }
                        }
                        throw $e;
                    }
                    
                    $transaction->commit();
                    
                    return $this->redirect(Url::to(['admin/show-products-detail', 'id'=>$productsModel->id]));
                }
            }
            
            return $this->redirect(Url::to(['admin/show-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет удалением товара
     */
    public function actionDeleteProducts()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_DELETE]);
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post())) {
                if ($productsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setProductsDelete([$productsModel])) {
                            throw new ErrorException('Ошибка при удалении категории!');
                        }
                        if (!empty($productsModel->images)) {
                            if (!PicturesHelper::deletePictures($productsModel->images)) {
                                throw new ErrorException('Ошибка при удалении изображений!');
                            }
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            return $this->redirect(Url::to(['admin/show-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует страницу выгрузки данных в csv
     * @return redirect
     */
    public function actionDataConvert()
    {
        try {
            $renderArray = array();
            $renderArray['productsModelFilter'] = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_ADMIN_FILTER]);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('convert.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует csv файл из товаров, сохраненных в БД
     * @return ajax
     */
    public function actionDownloadProducts()
    {
        try {
            if (\Yii::$app->request->isAjax) {
                
                FiltersHelper::addFiltersConvert();
                
                $resultArray = array();
                
                if (!empty($productsList = MappersHelper::getProductsList($this->_config))) {
                    $productsFile = CSVHelper::getCSV([
                        'path'=>\Yii::getAlias('@app/web/sources/csv/'),
                        'filename'=>'products' . time(),
                        'objectsArray'=>$productsList,
                        'fields'=>['id', 'date', 'code', 'name', 'short_description', 'price', 'images', 'active', 'total_products'],
                    ]);
                    
                    if (!empty($productsFile)) {
                        $resultArray['productsFile'] = $productsFile;
                    }
                }
                $response = \Yii::$app->response;
                $response->format = Response::FORMAT_JSON;
                return $resultArray;
            }
            
            return $this->redirect(Url::to(['admin/data-convert']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет текущим списком и добавлением категорий
     */
    public function actionShowAddCategories()
    {
        try {
            $categoriesModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FOR_ADD]);
            
            if (\Yii::$app->request->isPost && $categoriesModel->load(\Yii::$app->request->post())) {
                if ($categoriesModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setCategoriesInsert([$categoriesModel])) {
                            throw new ErrorException('Ошибка при сохранении категории!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            $renderArray = array();
            $renderArray['categoriesModel'] = $categoriesModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-add-categories.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет обновлением категории
     */
    public function actionUpdateCategories()
    {
        try {
            $categoriesModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FOR_UPDATE]);
            
            if (\Yii::$app->request->isPost && $categoriesModel->load(\Yii::$app->request->post())) {
                if ($categoriesModel->validate()) {
                    if (array_diff_assoc($categoriesModel->attributes, MappersHelper::getCategoriesById($categoriesModel)->attributes)) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setCategoriesUpdate([$categoriesModel])) {
                                throw new ErrorException('Ошибка при обновлении CategoriesModel!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                    return $this->redirect(Url::to(['admin/show-add-categories']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    return $this->redirect(Url::to(['admin/show-add-categories']));
                }
                
                if (empty($currentCategories = MappersHelper::getCategoriesById(new CategoriesModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-categories']));
                }
                \Yii::configure($categoriesModel, $currentCategories->attributes);
            }
            
            $renderArray = array();
            $renderArray['categoriesModel'] = $categoriesModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('update-categories.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет удалением категории
     */
    public function actionDeleteCategories()
    {
        try {
            $categoriesModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FOR_DELETE]);
            
            if (\Yii::$app->request->isPost && $categoriesModel->load(\Yii::$app->request->post())) {
                if ($categoriesModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setCategoriesDelete([$categoriesModel])) {
                            throw new ErrorException('Ошибка при удалении категории!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                    return $this->redirect(Url::to(['admin/show-add-categories']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentCategories = MappersHelper::getCategoriesById(new CategoriesModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-categories']));
                }
                \Yii::configure($categoriesModel, $currentCategories->attributes);
            }
            
            $renderArray = array();
            $renderArray['categoriesModel'] = $categoriesModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('delete-categories.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет текущим списком и добавлением подкатегорий
     */
    public function actionShowAddSubcategory()
    {
        try {
            $subcategoryModel = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FOR_ADD]);
            
            if (\Yii::$app->request->isPost && $subcategoryModel->load(\Yii::$app->request->post())) {
                if ($subcategoryModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setSubcategoryInsert([$subcategoryModel])) {
                            throw new ErrorException('Ошибка при сохранении категории!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            $renderArray = array();
            $renderArray['subcategoryModel'] = $subcategoryModel;
            $renderArray['productsModelFilter'] = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_ADMIN_FILTER]);
            if (!empty(\Yii::$app->filters->categories)) {
                $categoriesModel = MappersHelper::getCategoriesBySeocode(new CategoriesModel(['seocode'=>\Yii::$app->filters->categories]));
                \Yii::configure($renderArray['productsModelFilter'], ['id_categories'=>$categoriesModel->id]);
                $renderArray['subcategoryList'] = MappersHelper::getSubcategoryForCategoryList($categoriesModel);
            } else {
                $renderArray['subcategoryList'] = MappersHelper::getSubcategoryList();
            }
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-add-subcategory.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет обновлением подкатегории
     */
    public function actionUpdateSubcategory()
    {
        try {
            $subcategoryModel = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FOR_UPDATE]);
            
            if (\Yii::$app->request->isPost && $subcategoryModel->load(\Yii::$app->request->post())) {
                if ($subcategoryModel->validate()) {
                    if (array_diff_assoc($subcategoryModel->attributes, MappersHelper::getSubcategoryById($subcategoryModel)->attributes)) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setSubcategoryUpdate([$subcategoryModel])) {
                                throw new ErrorException('Ошибка при обновлении SubcategoryModel!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                    return $this->redirect(Url::to(['admin/show-add-subcategory']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                
                if (empty($currentSubcategory = MappersHelper::getSubcategoryById(new SubcategoryModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-subcategory']));
                }
                \Yii::configure($subcategoryModel, $currentSubcategory->attributes);
            }
            
            $renderArray = array();
            $renderArray['subcategoryModel'] = $subcategoryModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('update-subcategory.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет удалением подкатегории
     */
    public function actionDeleteSubcategory()
    {
        try {
            $subcategoryModel = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FOR_DELETE]);
            
            if (\Yii::$app->request->isPost && $subcategoryModel->load(\Yii::$app->request->post())) {
                if ($subcategoryModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setSubcategoryDelete([$subcategoryModel])) {
                            throw new ErrorException('Ошибка при удалении подкатегории!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                    return $this->redirect(Url::to(['admin/show-add-subcategory']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentSubcategory = MappersHelper::getSubcategoryById(new SubcategoryModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-subcategory']));
                }
                \Yii::configure($subcategoryModel, $currentSubcategory->attributes);
            }
            
            $renderArray = array();
            $renderArray['subcategoryModel'] = $subcategoryModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('delete-subcategory.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет текущим списком и добавлением brands
     */
    public function actionShowAddBrands()
    {
        try {
            $brandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_ADD]);
            
            if (\Yii::$app->request->isPost && $brandsModel->load(\Yii::$app->request->post())) {
                if ($brandsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setBrandsInsert([$brandsModel])) {
                            throw new ErrorException('Ошибка при сохранении бренда!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            $renderArray = array();
            $renderArray['brandsModel'] = $brandsModel;
            $renderArray['brandsList'] = MappersHelper::getBrandsList(false);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-add-brands.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет обновлением бренда
     */
    public function actionUpdateBrands()
    {
        try {
            $brandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_UPDATE]);
            
            if (\Yii::$app->request->isPost && $brandsModel->load(\Yii::$app->request->post())) {
                if ($brandsModel->validate()) {
                    if (array_diff_assoc($brandsModel->attributes, MappersHelper::getBrandsById($brandsModel)->attributes)) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setBrandsUpdate([$brandsModel])) {
                                throw new ErrorException('Ошибка при обновлении бренда!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                    return $this->redirect(Url::to(['admin/show-add-brands']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentBrands = MappersHelper::getBrandsById(new BrandsModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-brands']));
                }
                \Yii::configure($brandsModel, $currentBrands->attributes);
            }
            
            $renderArray = array();
            $renderArray['brandsModel'] = $brandsModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('update-brands.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет удалением бренда
     */
    public function actionDeleteBrands()
    {
        try {
            $brandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FOR_DELETE]);
            
            if (\Yii::$app->request->isPost && $brandsModel->load(\Yii::$app->request->post())) {
                if ($brandsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setBrandsDelete([$brandsModel])) {
                            throw new ErrorException('Ошибка при удалении бренда!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                    return $this->redirect(Url::to(['admin/show-add-brands']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentBrands = MappersHelper::getBrandsById(new BrandsModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-brands']));
                }
                \Yii::configure($brandsModel, $currentBrands->attributes);
            }
            
            $renderArray = array();
            $renderArray['brandsModel'] = $brandsModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('delete-brands.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет текущим списком и добавлением цветов
     */
    public function actionShowAddColors()
    {
        try {
            $colorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_ADD]);
            
            if (\Yii::$app->request->isPost && $colorsModel->load(\Yii::$app->request->post())) {
                if ($colorsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setColorsInsert([$colorsModel])) {
                            throw new ErrorException('Ошибка при сохранении цвета!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            $renderArray = array();
            $renderArray['colorsModel'] = $colorsModel;
            $renderArray['colorsList'] = MappersHelper::getColorsList(false);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-add-colors.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет обновлением цветов
     */
    public function actionUpdateColors()
    {
        try {
            $colorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_UPDATE]);
            
            if (\Yii::$app->request->isPost && $colorsModel->load(\Yii::$app->request->post())) {
                if ($colorsModel->validate()) {
                    if (array_diff_assoc($colorsModel->attributes, MappersHelper::getColorsById($colorsModel)->attributes)) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setColorsUpdate([$colorsModel])) {
                                throw new ErrorException('Ошибка при обновлении цвета!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                    return $this->redirect(Url::to(['admin/show-add-colors']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentColors = MappersHelper::getColorsById(new ColorsModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-colors']));
                }
                \Yii::configure($colorsModel, $currentColors->attributes);
            }
            
            $renderArray = array();
            $renderArray['colorsModel'] = $colorsModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('update-colors.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет удалением цвета
     */
    public function actionDeleteColors()
    {
        try {
            $colorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FOR_DELETE]);
            
            if (\Yii::$app->request->isPost && $colorsModel->load(\Yii::$app->request->post())) {
                if ($colorsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setColorsDelete([$colorsModel])) {
                            throw new ErrorException('Ошибка при удалении бренда!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                    return $this->redirect(Url::to(['admin/show-add-colors']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentColors = MappersHelper::getColorsById(new ColorsModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-colors']));
                }
                \Yii::configure($colorsModel, $currentColors->attributes);
            }
            
            $renderArray = array();
            $renderArray['colorsModel'] = $colorsModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('delete-colors.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет текущим списком и добавлением размеров
     */
    public function actionShowAddSizes()
    {
        try {
            $sizesModel = new SizesModel(['scenario'=>SizesModel::GET_FOR_ADD]);
            
            if (\Yii::$app->request->isPost && $sizesModel->load(\Yii::$app->request->post())) {
                if ($sizesModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setSizesInsert([$sizesModel])) {
                            throw new ErrorException('Ошибка при сохранении размера!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            $renderArray = array();
            $renderArray['sizesModel'] = $sizesModel;
            $renderArray['sizesList'] = MappersHelper::getSizesList(false);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-add-sizes.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет обновлением размеров
     */
    public function actionUpdateSizes()
    {
        try {
            $sizesModel = new SizesModel(['scenario'=>SizesModel::GET_FOR_UPDATE]);
            
            if (\Yii::$app->request->isPost && $sizesModel->load(\Yii::$app->request->post())) {
                if ($sizesModel->validate()) {
                    if (array_diff_assoc($sizesModel->attributes, MappersHelper::getSizesById($sizesModel)->attributes)) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setSizesUpdate([$sizesModel])) {
                                throw new ErrorException('Ошибка при обновлении размера!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                    return $this->redirect(Url::to(['admin/show-add-sizes']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentSizes = MappersHelper::getSizesById(new SizesModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-sizes']));
                }
                \Yii::configure($sizesModel, $currentSizes->attributes);
            }
            
            $renderArray = array();
            $renderArray['sizesModel'] = $sizesModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('update-sizes.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет удалением размера
     */
    public function actionDeleteSizes()
    {
        try {
            $sizesModel = new SizesModel(['scenario'=>SizesModel::GET_FOR_DELETE]);
            
            if (\Yii::$app->request->isPost && $sizesModel->load(\Yii::$app->request->post())) {
                if ($sizesModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setSizesDelete([$sizesModel])) {
                            throw new ErrorException('Ошибка при удалении бренда!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                    return $this->redirect(Url::to(['admin/show-add-sizes']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentSizes = MappersHelper::getSizesById(new SizesModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-sizes']));
                }
                \Yii::configure($sizesModel, $currentSizes->attributes);
            }
            
            $renderArray = array();
            $renderArray['sizesModel'] = $sizesModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('delete-sizes.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет текущим списком комментариев
     */
    public function actionShowComments()
    {
        try {
            if (empty(\Yii::$app->filters->getActive) && empty(\Yii::$app->filters->getNotActive)) {
                \Yii::$app->filters->getActive = true;
                \Yii::$app->filters->getNotActive = true;
            }
            
            $renderArray = array();
            $renderArray['commentsList'] = MappersHelper::getCommentsList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-comments.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет cut данные комментария в БД
     * @return redirect
     */
    public function actionUpdateCommentsCut()
    {
        try {
            $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FOR_UPDATE_CUT]);
            
            if (\Yii::$app->request->isPost && $commentsModel->load(\Yii::$app->request->post())) {
                if ($commentsModel->validate()) {
                    if ($commentsModel->active != MappersHelper::getCommentsById($commentsModel)->active) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setCommentsUpdate(['modelArray'=>[$commentsModel], 'fields'=>['id', 'active']])) {
                                throw new ErrorException('Ошибка при обновлении данных!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                }
            }
            
            return $this->redirect(Url::to(['admin/show-comments']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет данные комментария в БД
     * @return redirect
     */
    public function actionUpdateComments()
    {
        try {
            $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FOR_UPDATE]);
            
            if (\Yii::$app->request->isPost && $commentsModel->load(\Yii::$app->request->post())) {
                if ($commentsModel->validate()) {
                    
                    if (array_diff_assoc($commentsModel->toArray(), MappersHelper::getCommentsById($commentsModel)->toArray())) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!MappersHelper::setCommentsUpdate(['modelArray'=>[$commentsModel]])) {
                                throw new ErrorException('Ошибка при обновлении комментария!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                    
                    return $this->redirect(Url::to(['admin/show-comments']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentComments = MappersHelper::getCommentsById(new CommentsModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-comments']));
                }
                \Yii::configure($commentsModel, $currentComments->toArray());
            }
            
            $renderArray = array();
            $renderArray['commentsModel'] = $commentsModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('update-comments.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет данные комментария из БД
     * @return redirect
     */
    public function actionDeleteComments()
    {
        try {
            $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FOR_DELETE]);
            
            if (\Yii::$app->request->isPost && $commentsModel->load(\Yii::$app->request->post())) {
                if ($commentsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!MappersHelper::setCommentsDelete([$commentsModel])) {
                            throw new ErrorException('Ошибка при удалении комментария!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            return $this->redirect(Url::to(['admin/show-comments']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет текущим списком и добавлением валют
     * Основная валюта - валюта в котрой указаны цены товаров. Ее курс равен 1.00000
     * Курс остальных валют указыается относительно основной.
     * Так как в системе может быть только 1 основная валюта, 
     * в случае назначения новой основной валюты, значение main у текущей обнуляется,
     * значение свойства exchange_rate новой основной валюты принудительно выставляется в значение 1.00000
     */
    public function actionShowAddCurrency()
    {
        try {
            $currencyForAddModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_ADD]);
            
            if (\Yii::$app->request->isPost && $currencyForAddModel->load(\Yii::$app->request->post())) {
                if ($currencyForAddModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!empty($currencyForAddModel->main)) {
                            if (MappersHelper::getCurrencyByMain()) {
                                if (!MappersHelper::setCurrencyUpdateMainNull()) {
                                    throw new ErrorException('Ошибка при обнулении!');
                                }
                            }
                            $currencyForAddModel->exchange_rate = 1;
                        }
                        if (!MappersHelper::setCurrencyInsert([$currencyForAddModel])) {
                            throw new ErrorException('Ошибка при сохранении размера!');
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    $transaction->commit();
                }
            }
            
            $renderArray = array();
            $renderArray['currencyForAddModel'] = $currencyForAddModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-add-currency.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет обновлением валют
     */
    public function actionUpdateCurrency()
    {
        try {
            $currencyForUpdateModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_UPDATE]);
            
            if (\Yii::$app->request->isPost && $currencyForUpdateModel->load(\Yii::$app->request->post())) {
                if ($currencyForUpdateModel->validate()) {
                    
                    if (array_diff_assoc($currencyForUpdateModel->attributes, MappersHelper::getCurrencyById($currencyForUpdateModel)->attributes)) {
                        
                        $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                        
                        try {
                            if (!empty($currencyForUpdateModel->main)) {
                                if (MappersHelper::getCurrencyByMain()) {
                                    if (!MappersHelper::setCurrencyUpdateMainNull()) {
                                        throw new ErrorException('Ошибка при обнулении!');
                                    }
                                }
                                $currencyForUpdateModel->exchange_rate = 1;
                            }
                            if (!MappersHelper::setCurrencyUpdate([$currencyForUpdateModel])) {
                                throw new ErrorException('Ошибка при обновлении бренда!');
                            }
                        } catch(\Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                        }
                        
                        $transaction->commit();
                    }
                    
                    return $this->redirect(Url::to(['admin/show-add-currency']));
                }
            } else {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                    throw new ErrorException('Ошибка при получении ID!');
                }
                if (empty($currentCurrency = MappersHelper::getCurrencyById(new CurrencyModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])])))) {
                    return $this->redirect(Url::to(['admin/show-add-currency']));
                }
                \Yii::configure($currencyForUpdateModel, $currentCurrency->attributes);
            }
            
            $renderArray = array();
            $renderArray['currencyForUpdateModel'] = $currencyForUpdateModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('update-currency.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\ProductsFilterAdmin',
                'only'=>['show-products'],
            ],
            [
                'class'=>'app\filters\SubcategoryFilterAdmin',
                'only'=>['show-add-subcategory'],
            ],
            [
                'class'=>'app\filters\CommentsFilterAdmin',
                'only'=>['show-comments'],
            ],
        ];
    }
}
