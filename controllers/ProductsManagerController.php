<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use yii\base\ErrorException;
use yii\helpers\Url;
use app\helpers\MappersHelper;
use app\helpers\ModelsInstancesHelper;
use app\models\ProductsModel;

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
}
