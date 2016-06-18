<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
abstract class AbstractFiltersQueryCreator extends ProductsListQueryCreator
{
    /**
     * Инициирует создание SELECT запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
     */
    public function getSelectQuery()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters['tableOne']['firstTableName'],
                $this->categoriesArrayFilters['tableOne']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableOne']['secondTableName'],
                $this->categoriesArrayFilters['tableOne']['secondTableFieldOn']
            );
            
            if (in_array(\Yii::$app->params['categoryKey'], array_keys(\Yii::$app->request->get())) && !in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                $this->queryForCategory();
            } elseif (in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get())) && in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                $this->queryForSubCategory();
            }
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
            $this->forCategoryJoin();
            $this->forCategoryWhere();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    protected function forCategoryJoin()
    {
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
        $this->_mapperObject->params[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->request->get(\Yii::$app->params['categoryKey']);
    }
    
    protected function forCategoryWhere()
    {
        $this->_mapperObject->query .= $this->getWhere(
            $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
            \Yii::$app->params['categoryKey']
        );
    }
    
    /**
     * Формирует строку запроса к БД, если указана подкатегория
     * @return string
     */
    protected function queryForSubCategory()
    {
        try {
            $this->forCategoryJoin();
            $this->forSubCategoryJoin();
            $this->forCategoryWhere();
            $this->forSubCategoryWhere();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    protected function forSubCategoryJoin()
    {
        $this->_mapperObject->query .= $this->getJoin(
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
        );
        $this->_mapperObject->params[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']);
    }
    
    protected function forSubCategoryWhere()
    {
        $this->_mapperObject->query .= $this->getWhere(
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
            \Yii::$app->params['subCategoryKey']
        );
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
