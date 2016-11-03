<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\helpers\UrlHelper;

/**
 * Обрабатывает запросы на получение информации о конкретном продукте
 */
class ProductDetailController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос к 1 продукту
     */
    public function actionIndex()
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['productKey']))) {
                return $this->redirect(UrlHelper::previous('shop'));
            }
            
            $renderArray = ProductDetailControllerHelper::indexData();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('product-detail.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
