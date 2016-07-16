<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use yii\base\ErrorException;
use yii\web\Response;
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
            
            if (\Yii::$app->request->isPost && $productsModel->load(\Yii::$app->request->post())) {
                
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
     * Добавляет товар в БД
     * @return redirect
     */
    public function actionGetSubcategoryAjax()
    {
        try {
            $categoriesModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_FORM_FOR_SUBCATEGORY]);
            
            if (\Yii::$app->request->isAjax) {
                $categoriesModel->id = \Yii::$app->request->post('categoriesId');
                $response = \Yii::$app->response;
                $response->format = Response::FORMAT_JSON;
                $subcategoriesArray = MappersHelper::getSubcategoryForCategoryList($categoriesModel);
                return ArrayHelper::map($subcategoriesArray, 'id', 'name');
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
