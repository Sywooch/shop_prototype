<?php

namespace app\controllers;

use yii\base\ErrorException;
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
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $brandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $colorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $sizesModel = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM]);
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post()) && $brandsModel->load(\Yii::$app->request->post()) && $colorsModel->load(\Yii::$app->request->post()) && $sizesModel->load(\Yii::$app->request->post())) {
                $productsModel->imagesToLoad = UploadedFile::getInstances($productsModel, 'imagesToLoad');
                if ($productsModel->validate() && $brandsModel->validate() && $colorsModel->validate() && $sizesModel->validate()) {
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
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$productsModel->categories, 'subcategory'=>$productsModel->subcategory, 'id'=>$productsModel->id]));
                } else {
                    print_r($productsModel);
                    exit();
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
     * Управляет отображением всех товаров в базе
     */
    public function actionShowProducts()
    {
        try {
            $renderArray = array();
            $renderArray['objectsProductsList'] = MappersHelper::getProductsList($this->_config);
            $renderArray['productsModelFilter'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_ADMIN_FILTER]);
            if (!empty(\Yii::$app->filters->categories)) {
                \Yii::configure($renderArray['productsModelFilter'], ['id_categories'=>MappersHelper::getCategoriesBySeocode(new CategoriesModel(['seocode'=>\Yii::$app->filters->categories]))->id]);
            }
            if (!empty(\Yii::$app->filters->subcategory)) {
                \Yii::configure($renderArray['productsModelFilter'], ['id_subcategory'=>MappersHelper::getSubcategoryBySeocode(new SubcategoryModel(['seocode'=>\Yii::$app->filters->subcategory]))->id]);
            }
            if (!empty(\Yii::$app->filters->active)) {
                \Yii::configure($renderArray['productsModelFilter'], ['active'=>\Yii::$app->filters->active]);
            }
            $renderArray['colorsList'] = MappersHelper::getColorsList();
            $renderArray['sizesList'] = MappersHelper::getSizesList();
            $renderArray['brandsList'] = MappersHelper::getBrandsList();
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
    public function actionShowProductDetail()
    {
        try {
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
            }
            if (empty(\Yii::$app->request->get(\Yii::$app->params['idKey']))) {
                throw new ErrorException('Ошибка при получении ID продукта!');
            }
            
            $renderArray = array();
            $renderArray['objectsProducts'] = MappersHelper::getProductsById(new ProductsModel(['id'=>\Yii::$app->request->get(\Yii::$app->params['idKey'])]));
            \Yii::configure($renderArray['objectsProducts'], ['scenario'=>ProductsModel::GET_FROM_FORM_FOR_UPDATE]);
            $renderArray['brandsModel'] = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM, 'id'=>$renderArray['objectsProducts']->brands->id]);
            $renderArray['colorsModel'] = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT_FORM, 'idArray'=>ArrayHelper::getColumn($renderArray['objectsProducts']->colors, 'id')]);
            $renderArray['sizesModel'] = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM, 'idArray'=>ArrayHelper::getColumn($renderArray['objectsProducts']->sizes, 'id')]);
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
    public function actionUpdateProductCut()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_UPDATE_CUT]);
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post())) {
                if ($productsModel->validate()) {
                    if (!MappersHelper::setProductsUpdate(['productsModels'=>[$productsModel], 'fields'=>['id', 'active']])) {
                        throw new ErrorException('Ошибка при обновлении данных!');
                    }
                    return $this->redirect(Url::to(['admin/show-products']));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет full данные товара в БД
     * @return redirect
     */
    public function actionUpdateProduct()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_UPDATE]);
            $brandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $colorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $sizesModel = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM]);
            
            $updateConfig = array();
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post()) && $brandsModel->load(\Yii::$app->request->post()) && $colorsModel->load(\Yii::$app->request->post()) && $sizesModel->load(\Yii::$app->request->post())) {
                if (!empty($productsModel->imagesToLoad)) {
                    $productsModel->imagesToLoad = UploadedFile::getInstances($productsModel, 'imagesToLoad');
                }
                if ($productsModel->validate() && $brandsModel->validate() && $colorsModel->validate() && $sizesModel->validate()) {
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
                    $updateConfig['productsModels'][] = $productsModel;
                    if (!MappersHelper::setProductsUpdate($updateConfig)) {
                        throw new ErrorException('Ошибка при обновлении данных!');
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
                    
                    return $this->redirect(Url::to(['admin/show-product-detail', 'id'=>$productsModel->id]));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
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
            $renderArray['productsModelFilter'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_ADMIN_FILTER]);
            if (!empty(\Yii::$app->filters->active)) {
                \Yii::configure($renderArray['productsModelFilter'], ['active'=>\Yii::$app->filters->active]);
            }
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
                \Yii::$app->filters->clean();
                \Yii::$app->filters->cleanAdmin();
                
                FiltersHelper::addFiltersAdmin();
                
                if (!empty($objectsProductsList = MappersHelper::getProductsList($this->_config))) {
                    $productsFile = CSVHelper::getCSV([
                        'path'=>\Yii::getAlias('@app/web/sources/csv/'),
                        'filename'=>'products' . time(),
                        'objectsArray'=>$objectsProductsList,
                        'fields'=>['id', 'date', 'code', 'name', 'short_description', 'price', 'images', 'active', 'total_products'],
                    ]);
                    
                    $response = \Yii::$app->response;
                    $response->format = Response::FORMAT_JSON;
                    
                    return ['productsFile'=>$productsFile];
                }
            } else {
                return $this->redirect(Url::to(['admin/data-convert']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        } finally {
            \Yii::$app->filters->clean();
            \Yii::$app->filters->cleanAdmin();
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\ProductsListFilterAdmin',
                'only'=>['show-products', 'data-convert'],
            ],
            [
                'class'=>'app\filters\ProductsListFilterCSV',
                'only'=>['download-products'],
            ],
        ];
    }
}
