<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\data\Pagination;
use app\helpers\UrlHelper;
use app\controllers\{AbstractBaseController,
    ProductsListControllerHelper};
use app\actions\SearchAction;
use app\models\{ProductsCollection,
    ProductsModel};
use app\repositories\DbRepository;
use app\services\GoodsListSearchService;
use app\queries\LightPagination;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос к списку продуктов
     */
    /*public function actionIndex()
    {
        try {
            $renderArray = ProductsListControllerHelper::indexGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }*/
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов
     */
    public function actionSearch()
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return $this->redirect(UrlHelper::previous('shop'));
            }
            
            $renderArray = ProductsListControllerHelper::searchGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function actions()
    {
        return [
            'index'=>[
                'class'=>SearchAction::class,
                'service'=>new GoodsListSearchService([
                    'repository'=>new DbRepository([
                        'class'=>ProductsModel::class,
                        'collection'=>new ProductsCollection([
                            'pagination'=>new LightPagination()
                        ]),
                    ]),
                ]),
                'view'=>'products-list.twig'
            ],
        ];
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
