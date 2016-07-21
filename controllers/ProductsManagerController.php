<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\controllers\AbstractBaseController;
use app\helpers\MappersHelper;
use app\helpers\ModelsInstancesHelper;
use app\helpers\PicturesHelper;
use app\models\ProductsModel;
use app\models\CategoriesModel;
use app\models\BrandsModel;
use app\models\ColorsModel;
use app\models\SizesModel;

/**
 * Управляет добавлением, удалением, обновлением товаров
 */
class ProductsManagerController extends AbstractBaseController
{
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
                    if (!PicturesHelper::thumbnail($productsModelForAddProduct->imagesToLoad)) {
                        throw new ErrorException('Ошибка при обработке изображений!');
                    }
                    if(!$productsModelForAddProduct->upload()) {
                        throw new ErrorException('Ошибка при загрузке images!');
                    }
                    if (!MappersHelper::setProductsInsert($productsModelForAddProduct)) {
                        throw new ErrorException('Ошибка при сохранении!');
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
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray['brandsList'] = MappersHelper::getBrandsList();
            $renderArray['colorsList'] = MappersHelper::getColorsList();
            $renderArray['sizesList'] = MappersHelper::getSizesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('add-product.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив объектов subcategory для category
     * @return json
     */
    public function actionGetSubcategoryAjax()
    {
        try {
            if (\Yii::$app->request->isAjax) {
                if (!\Yii::$app->request->post('categoriesId')) {
                    throw new ErrorException('Невозможно получить значение categoriesId!');
                }
                $response = \Yii::$app->response;
                $response->format = Response::FORMAT_JSON;
                if (!$subcategoriesArray = MappersHelper::getSubcategoryForCategoryList(new CategoriesModel(['id'=>\Yii::$app->request->post('categoriesId')]))) {
                    throw new ErrorException('Ошибка при получении данных!');
                }
                return ArrayHelper::map($subcategoriesArray, 'id', 'name');
            } else {
                throw new ErrorException('Неверный тип запроса!');
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
