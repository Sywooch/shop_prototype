<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\sphinx\{MatchExpression,
    Query};
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\helpers\InstancesHelper;
use app\models\{BrandsModel,
    ColorsModel,
    ProductsModel,
    SizesModel};

/**
 * Коллекция сервис-методов ProductsListController
 */
class ProductsListControllerHelper extends AbstractControllerHelper
{
    /**
     * Конструирует данные для ProductsListController::actionIndex()
     * @return array
     */
    public static function indexData(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            $renderArray = ArrayHelper::merge($renderArray, self::getProductsPaginator());
            $renderArray = ArrayHelper::merge($renderArray, self::getColorsList());
            $renderArray = ArrayHelper::merge($renderArray, self::getSizesList());
            $renderArray = ArrayHelper::merge($renderArray, self::getBrandsList());
            $renderArray = ArrayHelper::merge($renderArray, self::getSorting());
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])], 'label'=>$renderArray['productsList'][0]->categoryName];
                if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                    \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['subcategoryKey']=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])], 'label'=>$renderArray['productsList'][0]->subcategoryName];
                }
            }
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для ProductsListController::actionSearch()
     * @return array
     */
    public static function searchData(): array
    {
        try {
            $sphinxArray = self::getSearch();
            
            $renderArray = InstancesHelper::getInstances();
            $renderArray = ArrayHelper::merge($renderArray, self::getProductsPaginator(!empty($sphinxArray) ? ['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')] : []));
            $renderArray = ArrayHelper::merge($renderArray, self::getColorsList($sphinxArray));
            $renderArray = ArrayHelper::merge($renderArray, self::getSizesList($sphinxArray));
            $renderArray = ArrayHelper::merge($renderArray, self::getBrandsList($sphinxArray));
            $renderArray = ArrayHelper::merge($renderArray, self::getSorting());
            
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Searching results')];
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными о товарах и пагинации для 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * @param array $extraWhere массив дополнительный условий, будет добавлен к WHERE
     * @return array
     */
    private static function getProductsPaginator(array $extraWhere=[]): array
    {
        try {
            $renderArray = [];
            
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'date', 'name', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'seocode']);
            $productsQuery->where(['[[products.active]]'=>true]);
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $productsQuery->addSelect(['[[categoryName]]'=>'[[categories.name]]']);
                $productsQuery->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $productsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                    $productsQuery->addSelect(['[[subcategoryName]]'=>'[[subcategory.name]]']);
                    $productsQuery->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                    $productsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            if (!empty($extraWhere)) {
                $productsQuery->andWhere($extraWhere);
            }
            $productsQuery->addFilters();
            $productsQuery->extendLimit();
            $sortingField = !empty(\Yii::$app->filters->sortingField) ? \Yii::$app->filters->sortingField : 'date';
            $sortingType = (!empty(\Yii::$app->filters->sortingType) && \Yii::$app->filters->sortingType === 'SORT_ASC') ? SORT_ASC : SORT_DESC;
            $productsQuery->orderBy(['[[products.' . $sortingField . ']]'=>$sortingType]);
            
            $renderArray['paginator'] = $productsQuery->paginator;
            
            $renderArray['productsList'] = $productsQuery->allArray();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными ColorsModel для фильтрации результатов 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * @params array $sphinxArray id товаров, найденные sphinx
     * @return array
     */
    private static function getColorsList(array $sphinxArray=[]): array
    {
        try {
            $renderArray = [];
            
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->distinct();
            $colorsQuery->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
            $colorsQuery->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
            $colorsQuery->where(['[[products.active]]'=>true]);
            
            if (!empty($sphinxArray)) {
                $colorsQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            } else {
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $colorsQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $colorsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                    if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                        $colorsQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $colorsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                    }
                }
            }
            
            $renderArray['colorsList'] = $colorsQuery->allMap('id', 'color');
            asort($renderArray['colorsList'], SORT_STRING);
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными SizesModel для фильтрации результатов 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * @params array $sphinxArray id товаров, найденные sphinx
     * @return array
     */
    private static function getSizesList(array $sphinxArray=[]): array
    {
        try {
            $renderArray = [];
            
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->distinct();
            $sizesQuery->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
            $sizesQuery->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
            $sizesQuery->where(['[[products.active]]'=>true]);
            
            if (!empty($sphinxArray)) {
                $sizesQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            } else {
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $sizesQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $sizesQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                    if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                        $sizesQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $sizesQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                    }
                }
            }
            
            $renderArray['sizesList'] = $sizesQuery->allMap('id', 'size');
            asort($renderArray['sizesList'], SORT_NUMERIC);
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными BrandsModel для фильтрации результатов 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * @params array $sphinxArray id товаров, найденные sphinx
     * @return array
     */
    private static function getBrandsList(array $sphinxArray=[]): array
    {
        try {
            $renderArray = [];
            
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->distinct();
            $brandsQuery->innerJoin('{{products}}', '[[products.id_brand]]=[[brands.id]]');
            $brandsQuery->where(['[[products.active]]'=>true]);
            
            if (!empty($sphinxArray)) {
                $brandsQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            } else {
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $brandsQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $brandsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                    if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                        $brandsQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $brandsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                    }
                }
            }
            
            $renderArray['brandsList'] = $brandsQuery->allMap('id', 'brand');
            asort($renderArray['brandsList'], SORT_STRING);
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными сортировки для фильтрации результатов 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * @return array
     */
    private static function getSorting(): array
    {
        try {
            $renderArray = [];
            
            $renderArray['sortingFieldList'] = ['date'=>\Yii::t('base', 'Sorting by date'), 'price'=>\Yii::t('base', 'Sorting by price')];
            $renderArray['sortingTypeList'] = ['SORT_DESC'=>\Yii::t('base', 'Sort descending'), 'SORT_ASC'=>\Yii::t('base', 'Sort ascending')];
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает ID записей, соответствующих поисковому запросу для 
     * - ProductsListController::actionSearch
     * @return array
     */
    private static function getSearch(): array
    {
        try {
            $sphinxQuery = new Query();
            $sphinxQuery->select(['id']);
            $sphinxQuery->from('{{shop}}');
            $sphinxQuery->match(new MatchExpression('[[@* :search]]', ['search'=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])]));
            $sphinxArray = $sphinxQuery->all();
            
            return $sphinxArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
