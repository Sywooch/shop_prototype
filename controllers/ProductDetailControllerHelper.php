<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\{ProductsModel,
    PurchasesModel};
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
            $renderArray['similarList'] = self::similar();
            $renderArray['relatedList'] = self::related();
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
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'code', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'seocode']);
            $productsQuery->addSelect(['[[categorySeocode]]'=>'[[categories.seocode]]', '[[categoryName]]'=>'[[categories.name]]', '[[subcategorySeocode]]'=>'[[subcategory.seocode]]', '[[subcategoryName]]'=>'[[subcategory.name]]']);
            $productsQuery->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
            $productsQuery->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
            $productsQuery->where(['[[products.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['productKey'])]);
            $productsQuery->with(['colors', 'sizes']);
            $productsQuery->asArray();
            self::$_productsModel = $productsQuery->one();
            
            return self::$_productsModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД массив ProductsModel похожих товаров
     * @return array
     */
    private static function similar(): array
    {
        try {
            $similarQuery = ProductsModel::find();
            $similarQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $similarQuery->distinct();
            $similarQuery->where(['!=', '[[products.id]]', self::$_productsModel['id']]);
            $similarQuery->andWhere(['[[products.id_category]]'=>self::$_productsModel['id_category']]);
            $similarQuery->andWhere(['[[products.id_subcategory]]'=>self::$_productsModel['id_subcategory']]);
            $similarQuery->innerJoin('{{products_colors}}', '[[products.id]]=[[products_colors.id_product]]');
            $similarQuery->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn(self::$_productsModel['colors'], 'id')]);
            $similarQuery->innerJoin('{{products_sizes}}', '[[products.id]]=[[products_sizes.id_product]]');
            $similarQuery->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn(self::$_productsModel['sizes'], 'id')]);
            $similarQuery->limit(\Yii::$app->params['similarLimit']);
            $similarQuery->orderBy(['[[products.date]]'=>SORT_DESC]);
            $similarQuery->asArray();
            $similarArray = $similarQuery->all();
            
            return $similarArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД массив ProductsModel связанных товаров
     * @return array
     */
    private static function related(): array
    {
        try {
            $relatedQuery = ProductsModel::find();
            $relatedQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $relatedQuery->innerJoin('{{related_products}}', '[[products.id]]=[[related_products.id_related_product]]');
            $relatedQuery->where(['[[related_products.id_product]]'=>self::$_productsModel['id']]);
            $relatedQuery->asArray();
            $relatedArray = $relatedQuery->all();
            ArrayHelper::multisort($relatedArray, 'date', SORT_DESC);
            
            return $relatedArray;
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
