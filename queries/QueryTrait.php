<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use app\models\{BrandsModel,
    ColorsModel,
    ProductsModel,
    SizesModel};

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
            
            $productsQuery->where(['[[products.active]]'=>true]);
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $productsQuery->innerJoin('categories', '[[categories.id]]=[[products.id_category]]');
                $productsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $productsQuery->innerJoin('subcategory', '[[subcategory.id]]=[[products.id_subcategory]]');
                $productsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
            }
            
            if (!empty($extraWhere)) {
                $productsQuery->andWhere($extraWhere);
            }
            
            if (!$productsQuery->addFilters()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'addFilters()']));
            }
            
            if (!$productsQuery->extendLimit()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'extendLimit()']));
            }
            
            $sortingField = !empty(\Yii::$app->filters->sortingField) ? \Yii::$app->filters->sortingField : 'date';
            $sortingType = (!empty(\Yii::$app->filters->sortingType) && \Yii::$app->filters->sortingType === 'SORT_ASC') ? SORT_ASC : SORT_DESC;
            $productsQuery->orderBy(['[[products.' . $sortingField . ']]'=>$sortingType]);
            
            return $productsQuery;
        } catch (\Throwable $t) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $t->getMessage());
        }
    }
    
    /**
     * Конструирует ActiveQuery для выборки объектов 
     * ColorsModel в контексте фильтрации выборки ProductsModel
     * @return ActiveQuery
     */
    private function colorsListQuery(): ActiveQuery
    {
        try {
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->distinct();
            $colorsQuery->innerJoin('products_colors', '[[colors.id]]=[[products_colors.id_color]]');
            $colorsQuery->innerJoin('products', '[[products_colors.id_product]]=[[products.id]]');
            $colorsQuery->where(['[[products.active]]'=>true]);
            if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                $colorsQuery->innerJoin('categories', '[[products.id_category]]=[[categories.id]]');
                $colorsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                    $colorsQuery->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $colorsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            $colorsQuery->orderBy(['[[colors.color]]'=>SORT_ASC]);
            
            return $colorsQuery;
        } catch (\Throwable $t) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $t->getMessage());
        }
    }
    
    /**
     * Конструирует ActiveQuery для выборки объектов 
     * SizesModel в контексте фильтрации выборки ProductsModel
     * @return ActiveQuery
     */
    private function sizesListQuery(): ActiveQuery
    {
        try {
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->distinct();
            $sizesQuery->innerJoin('products_sizes', '[[sizes.id]]=[[products_sizes.id_size]]');
            $sizesQuery->innerJoin('products', '[[products_sizes.id_product]]=[[products.id]]');
            $sizesQuery->where(['[[products.active]]'=>true]);
            if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                $sizesQuery->innerJoin('categories', '[[products.id_category]]=[[categories.id]]');
                $sizesQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                    $sizesQuery->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $sizesQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            $sizesQuery->orderBy(['[[sizes.size]]'=>SORT_ASC]);
            
            return $sizesQuery;
        } catch (\Throwable $t) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $t->getMessage());
        }
    }
    
    /**
     * Конструирует ActiveQuery для выборки объектов 
     * BrandsModel в контексте фильтрации выборки ProductsModel
     * @return ActiveQuery
     */
    private function brandsListQuery(): ActiveQuery
    {
        try {
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->distinct();
            $brandsQuery->innerJoin('products_brands', '[[brands.id]]=[[products_brands.id_brand]]');
            $brandsQuery->innerJoin('products', '[[products_brands.id_product]]=[[products.id]]');
            $brandsQuery->where(['[[products.active]]'=>true]);
            if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                $brandsQuery->innerJoin('categories', '[[products.id_category]]=[[categories.id]]');
                $brandsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                    $brandsQuery->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $brandsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            $brandsQuery->orderBy(['[[brands.brand]]'=>SORT_ASC]);
            
            return $brandsQuery;
        } catch (\Throwable $t) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $t->getMessage());
        }
    }
    
    /**
     * Конструирует ActiveQuery для выборки объектов 
     * ColorsModel в контексте фильтрации выборки результатов поиска ProductsModel 
     * @params array $sphinxArray массив id ProductsModel
     * @return ActiveQuery
     */
    private function colorsListQuerySearch(array $sphinxArray): ActiveQuery
    {
        try {
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->distinct();
            $colorsQuery->innerJoin('products_colors', '[[colors.id]]=[[products_colors.id_color]]');
            $colorsQuery->innerJoin('products', '[[products_colors.id_product]]=[[products.id]]');
            $colorsQuery->where(['[[products.active]]'=>true]);
            $colorsQuery->andWhere(['[[products.id]]'=>$sphinxArray]);
            $colorsQuery->orderBy(['[[colors.color]]'=>SORT_ASC]);
            
            return $colorsQuery;
        } catch (\Throwable $t) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $t->getMessage());
        }
    }
    
    /**
     * Конструирует ActiveQuery для выборки объектов 
     * SizesModel в контексте фильтрации выборки результатов поиска ProductsModel
     * @params array $sphinxArray массив id ProductsModel
     * @return ActiveQuery
     */
    private function sizesListQuerySearch(array $sphinxArray): ActiveQuery
    {
        try {
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->distinct();
            $sizesQuery->innerJoin('products_sizes', '[[sizes.id]]=[[products_sizes.id_size]]');
            $sizesQuery->innerJoin('products', '[[products_sizes.id_product]]=[[products.id]]');
            $sizesQuery->where(['[[products.active]]'=>true]);
            $sizesQuery->andWhere(['[[products.id]]'=>$sphinxArray]);
            $sizesQuery->orderBy(['[[sizes.size]]'=>SORT_ASC]);
            
            return $sizesQuery;
        } catch (\Throwable $t) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $t->getMessage());
        }
    }
    
    /**
     * Конструирует ActiveQuery для выборки объектов 
     * BrandsModel в контексте фильтрации выборки результатов поиска ProductsModel 
     * @params array $sphinxArray массив id ProductsModel
     * @return ActiveQuery
     */
    private function brandsListQuerySearch(array $sphinxArray): ActiveQuery
    {
        try {
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->distinct();
            $brandsQuery->innerJoin('products_brands', '[[brands.id]]=[[products_brands.id_brand]]');
            $brandsQuery->innerJoin('products', '[[products_brands.id_product]]=[[products.id]]');
            $brandsQuery->where(['[[products.active]]'=>true]);
            $brandsQuery->andWhere(['[[products.id]]'=>$sphinxArray]);
            $brandsQuery->orderBy(['[[brands.brand]]'=>SORT_ASC]);
            
            return $brandsQuery;
        } catch (\Throwable $t) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $t->getMessage());
        }
    }
}
