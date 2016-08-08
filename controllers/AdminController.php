<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\UploadedFile;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\helpers\{MappersHelper, 
    ModelsInstancesHelper, 
    PicturesHelper};
use app\models\{ProductsModel, 
    CategoriesModel, 
    BrandsModel, 
    ColorsModel, 
    SizesModel};

/**
 * Управляет административными функциями
 */
class AdminController extends AbstractBaseController
{
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
    public function actionAddProduct()
    {
        try {
            $productsModelForAddProduct = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $brandsModelForAddToCart = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $colorsModelForAddToCart = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT_FORM]);
            $sizesModelForAddToCart = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM]);
            
            if (\Yii::$app->request->isPost && $productsModelForAddProduct->load(\Yii::$app->request->post()) && $brandsModelForAddToCart->load(\Yii::$app->request->post()) && $colorsModelForAddToCart->load(\Yii::$app->request->post()) && $sizesModelForAddToCart->load(\Yii::$app->request->post())) {
                $productsModelForAddProduct->imagesToLoad = UploadedFile::getInstances($productsModelForAddProduct, 'imagesToLoad');
                if ($productsModelForAddProduct->validate() && $brandsModelForAddToCart->validate() && $colorsModelForAddToCart->validate() && $sizesModelForAddToCart->validate()) {
                    if (!PicturesHelper::createPictures($productsModelForAddProduct->imagesToLoad)) {
                        throw new ErrorException('Ошибка при обработке изображений!');
                    }
                    if(!$productsModelForAddProduct->upload()) {
                        throw new ErrorException('Ошибка при загрузке images!');
                    }
                    if(!PicturesHelper::createThumbnails(\Yii::getAlias('@pic/' . $productsModelForAddProduct->images))) {
                        throw new ErrorException('Ошибка при загрузке images!');
                    }
                    if (!MappersHelper::setProductsInsert($productsModelForAddProduct)) {
                        throw new ErrorException('Ошибка при сохранении продукта!');
                    }
                    if (!MappersHelper::setProductsBrandsInsert($productsModelForAddProduct, $brandsModelForAddToCart)) {
                        throw new ErrorException('Ошибка при сохранении связи продукта с брендом!');
                    }
                    if (!MappersHelper::setProductsColorsInsert($productsModelForAddProduct, $colorsModelForAddToCart)) {
                        throw new ErrorException('Ошибка при сохранении связи продукта с colors!');
                    }
                    if (!MappersHelper::setProductsSizesInsert($productsModelForAddProduct, $sizesModelForAddToCart)) {
                        throw new ErrorException('Ошибка при сохранении связи продукта с colors!');
                    }
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$productsModelForAddProduct->categories, 'subcategory'=>$productsModelForAddProduct->subcategory, 'id'=>$productsModelForAddProduct->id]));
                }
            }
            
            $renderArray = array();
            $renderArray['productsModelForAddProduct'] = $productsModelForAddProduct;
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
            $renderArray['colorsList'] = MappersHelper::getColorsList();
            $renderArray['sizesList'] = MappersHelper::getSizesList();
            $renderArray['brandsList'] = MappersHelper::getBrandsList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('products-list.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
