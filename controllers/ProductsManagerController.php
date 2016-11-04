<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\{AbstractBaseController,
    ProductsManagerControllerHelper};

/**
 * Управляет добавлением, удвлением, изменением товаров
 */
class ProductsManagerController extends AbstractBaseController
{
    /**
     * Управляет процессом добавления 1 товара
     */
    public function actionAddOne()
    {
        try {
            if (\Yii::$app->request->isAjax) {
                return ProductsManagerControllerHelper::addOneAjax();
            }
            
            if (\Yii::$app->request->isPost) {
                if ($seocode = ProductsManagerControllerHelper::addOnePost()) {
                    return $this->redirect(Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$seocode]));
                }
            }
            
            $renderArray = ProductsManagerControllerHelper::addOneGet();
            
            return $this->render('add-one.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
