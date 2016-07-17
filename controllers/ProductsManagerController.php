<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use yii\base\ErrorException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\helpers\MappersHelper;
use app\helpers\ModelsInstancesHelper;
use app\models\ProductsModel;
use app\models\CategoriesModel;

/**
 * Управляет добавлением, удалением, обновлением товаров
 */
class ProductsManagerController extends AbstractBaseController
{
    /**
     * Добавляет товар в БД
     * @return redirect
     */
    public function actionAddProduct()
    {
        try {
            $productsModelForAddProduct = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT_FORM]);
            
            if (\Yii::$app->request->isPost && $productsModelForAddProduct->load(\Yii::$app->request->post())) {
                $productsModelForAddProduct->imagesToLoad = UploadedFile::getInstances($productsModelForAddProduct, 'imagesToLoad');
                if ($productsModelForAddProduct->validate()) {
                    if(!$productsModelForAddProduct->upload()) {
                        throw new ErrorException('Ошибка при загрузке images!');
                    }
                    return $this->redirect(Url::to(['products-list/index']));
                }
            }
            
            $renderArray = array();
            $renderArray['productsModelForAddProduct'] = $productsModelForAddProduct;
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
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
