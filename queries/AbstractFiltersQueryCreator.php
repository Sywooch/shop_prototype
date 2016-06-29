<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;
use yii\base\ErrorException;

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
            
            if (in_array(\Yii::$app->params['categoryKey'], array_keys(\Yii::$app->request->get())) && !in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            } elseif (in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get())) && in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForSubCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            }
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
    
    protected function forCategoryJoin()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            
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
            
            $this->_mapperObject->params[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->request->get(\Yii::$app->params['categoryKey']);
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
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
            $this->_mapperObject->params[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']);
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
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
