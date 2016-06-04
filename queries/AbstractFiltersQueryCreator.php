<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
abstract class AbstractFiltersQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'categories'=>[ # Данные для выборки из таблицы categories
            'firstTableName'=>'products', # Имя первой таблицы участвующей в объединении
            'firstTableFieldOn'=>'id_categories', # Имя поля первой таблицы, по которому проходит объединение
            'secondTableName'=>'categories', # Имя второй таблицы участвующей в объединении
            'secondTableFieldOn'=>'id', # Имя поля второй таблицы, по которому проходит объединение
            'secondTableFieldWhere'=>'seocode', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
        ],
        'subcategory'=>[ # Данные для выборки из таблицы subcategory
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id_subcategory',
            'secondTableName'=>'subcategory',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'seocode',
        ],
    ];
    
    /**
     * Формирует строку запроса к БД
     * @return string
     */
    protected function queryForAll()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters['tableOne']['firstTableName'],
                $this->categoriesArrayFilters['tableOne']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableOne']['secondTableName'],
                $this->categoriesArrayFilters['tableOne']['secondTableFieldOn']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, если указана категория
     * @return string
     */
    protected function queryForCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters['tableOne']['firstTableName'],
                $this->categoriesArrayFilters['tableOne']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableOne']['secondTableName'],
                $this->categoriesArrayFilters['tableOne']['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters['tableTwo']['firstTableName'],
                $this->categoriesArrayFilters['tableTwo']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableTwo']['secondTableName'],
                $this->categoriesArrayFilters['tableTwo']['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['categoryKey']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->categoryFlag = true;
    }
    
    /**
     * Формирует строку запроса к БД, если указана подкатегория
     * @return string
     */
    protected function queryForSubCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters['tableOne']['firstTableName'],
                $this->categoriesArrayFilters['tableOne']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableOne']['secondTableName'],
                $this->categoriesArrayFilters['tableOne']['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters['tableTwo']['firstTableName'],
                $this->categoriesArrayFilters['tableTwo']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableTwo']['secondTableName'],
                $this->categoriesArrayFilters['tableTwo']['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['categoryKey']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['subCategoryKey']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->categoryFlag = true;
        $this->_mapperObject->subcategoryFlag = true;
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     */
    protected function addSelectHead()
    {
        $this->_mapperObject->query = 'SELECT DISTINCT ';
        $this->_mapperObject->query .= $this->addFields();
        $this->_mapperObject->query .= $this->addTableName();
    }
}
