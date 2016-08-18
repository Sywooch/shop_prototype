<?php

namespace app\queries;

use yii\base\ErrorException;
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
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            if (!$this->addSelectHead()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $join = $this->getJoin(
                $this->categoriesArrayFilters['tableOne']['firstTableName'],
                $this->categoriesArrayFilters['tableOne']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableOne']['secondTableName'],
                $this->categoriesArrayFilters['tableOne']['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, если указана категория
     * @return boolean
     */
    protected function queryForCategory()
    {
        try {
            if (!$this->forCategoryJoin()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->forCategoryWhere()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, добавляя таблицу products
     * @return boolean
     */
    protected function productsJoin()
    {
        try {
            $join = $this->getJoin(
                $this->categoriesArrayFilters['tableTwo']['firstTableName'],
                $this->categoriesArrayFilters['tableTwo']['firstTableFieldOn'],
                $this->categoriesArrayFilters['tableTwo']['secondTableName'],
                $this->categoriesArrayFilters['tableTwo']['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, добавляя join categories
     * @return boolean
     */
    protected function forCategoryJoin()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            
            $join = $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            if (\Yii::$app->filters->categories) {
                $categoriesValue = \Yii::$app->filters->categories;
            } else {
                $categoriesValue = \Yii::$app->request->get(\Yii::$app->params['categoryKey']);
            }
            $this->_mapperObject->params[':' . \Yii::$app->params['categoryKey']] = $categoriesValue;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, добавляя where categories
     * @return boolean
     */
    protected function forCategoryWhere()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['categoryKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, если указана подкатегория
     * @return boolean
     */
    protected function queryForSubCategory()
    {
        try {
            if (!$this->forCategoryJoin()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->forSubCategoryJoin()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->forCategoryWhere()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->forSubCategoryWhere()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, добавляя join subcategory
     * @return boolean
     */
    protected function forSubCategoryJoin()
    {
        try {
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            $join = $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            if (\Yii::$app->filters->subcategory) {
                $subcategoryValue = \Yii::$app->filters->subcategory;
            } else {
                $subcategoryValue = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']);
            }
            $this->_mapperObject->params[':' . \Yii::$app->params['subCategoryKey']] = $subcategoryValue;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, добавляя where subcategory
     * @return boolean
     */
    protected function forSubCategoryWhere()
    {
        try {
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            $where = $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['subCategoryKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     * @return boolean
     */
    protected function addSelectHead()
    {
        try {
            $this->_mapperObject->query = 'SELECT DISTINCT ';
            $fields = $this->addFields();
            if (!is_string($fields)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $fields;
            $name = $this->addTableName();
            if (!is_string($name)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $name;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
