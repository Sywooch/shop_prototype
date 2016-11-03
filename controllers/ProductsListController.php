<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\helpers\UrlHelper;
use app\controllers\{AbstractBaseController,
    ProductsListControllerHelper};

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос к списку продуктов
     */
    public function actionIndex()
    {
        try {
            $renderArray = ProductsListControllerHelper::indexData();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов
     */
    public function actionSearch()
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return $this->redirect(UrlHelper::previous('shop'));
            }
            
            $renderArray = ProductsListControllerHelper::searchData();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\ProductsFilter',
            ],
            [
                'class'=>'app\filters\CurrencyFilter',
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
