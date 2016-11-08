<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\PurchasesModel;
use app\helpers\InstancesHelper;

/**
 * Коллекция сервис-методов ProductDetailController
 */
class ProductDetailControllerHelper extends AbstractControllerHelper
{
    /**
     * @var array массив данных основного товара, полученный из БД
     */
    private static $_productsModel;
    
    /**
     * Конструирует данные для ProductDetailController::actionIndex()
     * @return array
     */
    public static function indexGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            $renderArray['productsModel'] = self::main();
            $renderArray['similarList'] = self::getSimilarProducts(self::$_productsModel, true);
            $renderArray['relatedList'] = self::getRelatedProducts(self::$_productsModel['id'], true);
            $renderArray['purchasesModel'] = new PurchasesModel(['quantity'=>1]);
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД объект ProductsModel представляющий запрошенный товар
     * @return array
     */
    private static function main(): array
    {
        try {
            self::$_productsModel = self::getProduct(\Yii::$app->request->get(\Yii::$app->params['productKey']), true, true, true);
            
            return self::$_productsModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs'] 
     */
    private static function breadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            if (!empty(self::$_productsModel['categorySeocode']) && !empty(self::$_productsModel['categoryName'])) {
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>self::$_productsModel['categorySeocode']], 'label'=>self::$_productsModel['categoryName']];
                if (!empty(self::$_productsModel['subcategorySeocode']) && !empty(self::$_productsModel['subcategoryName'])) {
                    \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>self::$_productsModel['categorySeocode'], \Yii::$app->params['subcategoryKey']=>self::$_productsModel['subcategorySeocode']], 'label'=>self::$_productsModel['subcategoryName']];
                }
            }
            if (!empty(self::$_productsModel['seocode']) && !empty(self::$_productsModel['name'])) {
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/product-detail/index', \Yii::$app->params['productKey']=>self::$_productsModel['seocode']], 'label'=>self::$_productsModel['name']];
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
