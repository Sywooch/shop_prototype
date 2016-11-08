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
     * @var array массив товаров, полученный из БД
     */
    private static $_productsList;
    
    /**
     * Конструирует данные для ProductsListController::actionIndex()
     * @return array
     */
    public static function indexGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            $renderArray = ArrayHelper::merge($renderArray, self::getProductsPaginator([], true));
            $renderArray['colorsList'] = self::colorsMap([], true);
            $renderArray['sizesList'] = self::sizesMap([], true);
            $renderArray['brandsList'] = self::brandsMap([], true);
            $renderArray = ArrayHelper::merge($renderArray, self::getSorting());
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для ProductsListController::actionSearch()
     * @return array
     */
    public static function searchGet(): array
    {
        try {
            $sphinxArray = self::getSearch();
            
            $renderArray = InstancesHelper::getInstances();
            
            $renderArray = ArrayHelper::merge($renderArray, self::getProductsPaginator(!empty($sphinxArray) ? ['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')] : [], true));
            $renderArray['colorsList'] = self::colorsMap($sphinxArray, true);
            $renderArray['sizesList'] = self::sizesMap($sphinxArray, true);
            $renderArray['brandsList'] = self::brandsMap($sphinxArray, true);
            $renderArray = ArrayHelper::merge($renderArray, self::getSorting());
            
            self::searchBreadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных о товарах и пагинации
     * @param array $extraWhere массив дополнительный условий, будет добавлен к WHERE
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function getProductsPaginator(array $extraWhere=[], bool $asArray=false): array
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
            
            if (!empty($asArray)) {
                $productsQuery->asArray();
            }
            self::$_productsList = $productsQuery->all();
            $renderArray['productsList'] = self::$_productsList;
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ColorsModel 
     * @params array $sphinxArray id товаров, найденные sphinx
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function colorsMap(array $sphinxArray=[], bool $asArray=false): array
    {
        try {
            $colorsArray = self::getColorsJoinProducts($sphinxArray, $asArray);
            $colorsArray = ArrayHelper::map($colorsArray, 'id', 'color');
            asort($colorsArray, SORT_STRING);
            
            return $colorsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив SizesModel 
     * @params array $sphinxArray id товаров, найденные sphinx
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function sizesMap(array $sphinxArray=[], bool $asArray=false): array
    {
        try {
            $sizesArray = self::getSizesJoinProducts($sphinxArray, $asArray);
            $sizesArray = ArrayHelper::map($sizesArray, 'id', 'size');
            asort($sizesArray, SORT_STRING);
            
            return $sizesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив BrandsModel 
     * @params array $sphinxArray id товаров, найденные sphinx
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function brandsMap(array $sphinxArray=[], bool $asArray=false): array
    {
        try {
            $brandsArray = self::getBrandsJoinProducts($sphinxArray, $asArray);
            $brandsArray = ArrayHelper::map($brandsArray, 'id', 'brand');
            asort($brandsArray, SORT_STRING);
            
            return $brandsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными сортировки для фильтрации результатов 
     * выборки из БД
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
     * Получает ID записей, соответствующих поисковому запросу
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
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs']
     */
    private static function breadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])], 'label'=>self::$_productsList[0]['categoryName']];
                if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                    \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['subcategoryKey']=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])], 'label'=>self::$_productsList[0]['subcategoryName']];
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs']
     */
    private static function searchBreadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Searching results')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
