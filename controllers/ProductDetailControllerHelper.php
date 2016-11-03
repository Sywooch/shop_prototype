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
     * Конструирует данные для ProductDetailController::actionIndex()
     * @return array
     */
    public static function indexData(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'code', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'seocode']);
            $productsQuery->addSelect(['[[categorySeocode]]'=>'[[categories.seocode]]', '[[categoryName]]'=>'[[categories.name]]', '[[subcategorySeocode]]'=>'[[subcategory.seocode]]', '[[subcategoryName]]'=>'[[subcategory.name]]']);
            $productsQuery->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
            $productsQuery->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
            $productsQuery->where(['[[products.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['productKey'])]);
            $productsQuery->with(['colors', 'sizes']);
            $productsModel = $productsQuery->oneArray();
            $renderArray['productsModel'] = $productsModel;
            
            $similarQuery = ProductsModel::find();
            $similarQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $similarQuery->distinct();
            $similarQuery->where(['!=', '[[products.id]]', $renderArray['productsModel']->id]);
            $similarQuery->andWhere(['[[products.id_category]]'=>$renderArray['productsModel']->id_category]);
            $similarQuery->andWhere(['[[products.id_subcategory]]'=>$renderArray['productsModel']->id_subcategory]);
            $similarQuery->innerJoin('{{products_colors}}', '[[products.id]]=[[products_colors.id_product]]');
            $similarQuery->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($renderArray['productsModel']->colors, 'id')]);
            $similarQuery->innerJoin('{{products_sizes}}', '[[products.id]]=[[products_sizes.id_product]]');
            $similarQuery->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($renderArray['productsModel']->sizes, 'id')]);
            $similarQuery->limit(\Yii::$app->params['similarLimit']);
            $similarQuery->orderBy(['[[products.date]]'=>SORT_DESC]);
            $renderArray['similarList'] = $similarQuery->allArray();
            
            $relatedQuery = ProductsModel::find();
            $relatedQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $relatedQuery->innerJoin('{{related_products}}', '[[products.id]]=[[related_products.id_related_product]]');
            $relatedQuery->where(['[[related_products.id_product]]'=>$renderArray['productsModel']->id]);
            $renderArray['relatedList'] = $relatedQuery->allArray();
            ArrayHelper::multisort($renderArray['relatedList'], 'date', SORT_DESC);
            
            $renderArray['purchasesModel'] = new PurchasesModel(['quantity'=>1]);
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            if (!empty($productsModel->categorySeocode) && !empty($productsModel->categoryName)) {
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$productsModel->categorySeocode], 'label'=>$productsModel->categoryName];
                if (!empty($productsModel->subcategorySeocode) && !empty($productsModel->subcategoryName)) {
                    \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$productsModel->categorySeocode, \Yii::$app->params['subcategoryKey']=>$productsModel->subcategorySeocode], 'label'=>$productsModel->subcategoryName];
                }
            }
            if (!empty($productsModel->seocode) && !empty($productsModel->name)) {
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/product-detail/index', \Yii::$app->params['productKey']=>$productsModel->seocode], 'label'=>$productsModel->name];
            }
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
