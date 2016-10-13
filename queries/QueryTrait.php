<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use app\models\{ColorsModel,
    ProductsModel};

/**
 * Коллекция методов для конструирования Query объектов 
 */
trait QueryTrait
{
    /**
     * Инкапсулирует общую для 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * функциональность
     * @param array $extraWhere массив дополнительный условий, будет добавлен к WHERE
     * @return ActiveQuery
     */
    private function productsListQuery(array $extraWhere=[]): ActiveQuery
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'date', 'name', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'seocode']);
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $productsQuery->innerJoin('categories', '[[categories.id]]=[[products.id_category]]');
                $productsQuery->andWhere(['categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $productsQuery->innerJoin('subcategory', '[[subcategory.id]]=[[products.id_subcategory]]');
                $productsQuery->andWhere(['subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
            }
            
            $productsQuery->andWhere(['products.active'=>true]);
            
            if (!empty($extraWhere)) {
                $productsQuery->andWhere($extraWhere);
            }
            
            if (!$productsQuery->addFilters()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'addFilters()']));
            }
            
            if (!$productsQuery->extendLimit()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'extendLimit()']));
            }
            
            $sortingField = \Yii::$app->filters->sortingField ? \Yii::$app->filters->sortingField : 'date';
            $sortingType = (\Yii::$app->filters->sortingType && \Yii::$app->filters->sortingType === 'ASC') ? SORT_ASC : SORT_DESC;
            $productsQuery->orderBy(['products.' . $sortingField=>$sortingType]);
            
            return $productsQuery;
        } catch (\Exception $e) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $e->getMessage());
        }
    }
    
    /**
     * Конструирует ActiveQuery для выборки объектов 
     * ColorsModel в контексте фильтрации выборки списка продуктов
     * @param array $extraWhere массив дополнительный условий, будет добавлен к WHERE
     * @return ActiveQuery
     */
    private function colorsListQuery(): ActiveQuery
    {
        try {
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->distinct();
            $colorsQuery->innerJoin('products_colors', '[[colors.id]]=[[products_colors.id_color]]');
            
            if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                $productsQuery = ProductsModel::find();
                $productsQuery->extendSelect(['id']);
                $productsQuery->innerJoin('categories', '[[products.id_category]]=[[categories.id]]');
                $productsQuery->where(['categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                    $productsQuery->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $productsQuery->andWhere(['subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            
                $colorsQuery->where(['products_colors.id_product'=>ArrayHelper::getColumn($productsQuery->all(), 'id')]);
            }
            
            return $colorsQuery;
        } catch (\Exception $e) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $e->getMessage());
        }
    }
}
