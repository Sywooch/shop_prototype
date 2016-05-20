<?php

namespace app\mappers;

use app\mappers\BaseAbstractMapper;
use yii\base\ErrorException;

class ProductListMapper extends BaseAbstractMapper
{
    /**
     * @var array массив имен фильтров, которые могут быть переданы в $_GET
     */
    public $filterKeys;
    /**
     * @var int максимальное кол-во возвращаемых записей
     */
    public $limit;
    /**
     * @var string имя таблицы, источника данных
     */
    public $tableName = 'products';
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->filterKeys)) {
            $this->filterKeys = \Yii::$app->params['filterKeys'];
        }
        
        if (!isset($this->limit)) {
            $this->limit = \Yii::$app->params['limit'];
        }
        
        if (!isset($this->orderByRoute)) {
            $this->orderByRoute = \Yii::$app->params['orderByRoute'];
        }
    }
    
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getGroup()
    {
        try {
            $getKeys = array_keys(\Yii::$app->request->get());
            if (in_array('category', $getKeys)) {
                $this->queryForCategory();
            } else if (in_array('subcategory', $getKeys)) {
                $this->queryForSubCategory();
            } else {
                $this->queryForAll();
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getGroup\n" . $e->getMessage());
        }
        return $this->_query;
    }
    
    /**
     * Возвращает сформированную строку запроса к БД
     * @return string
     */
    private function queryForAll()
    {
        try {
            $this->getSelectHead();
            $this->_query .= $this->addFilters();
            $this->_query .= $this->getOrder();
            $this->_query .= $this->getLimit();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::queryForAll\n" . $e->getMessage());
        }
        return $this->_query;
    }
    
    /**
     * Возвращает сформированную строку запроса к БД, фильруя по категории
     * @return string
     */
    public function queryForCategory()
    {
        try {
            $this->getSelectHead();
            $this->_query .= ' WHERE category=:category';
            $this->_query .= $this->addFilters();
            $this->_query .= $this->getOrder();
            $this->_query .= $this->getLimit();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::queryForCategory\n" . $e->getMessage());
        }
        return $this->_query;
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     */
    private function getSelectHead()
    {
        try {
            $this->_query = 'SELECT ';
            $this->_query .= $this->getFields();
            $this->_query .= $this->getTableName();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getSelectHead\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует часть запроса к БД, перечисляющую столбцы данных, которые необходимо включить в выборку
     * @return string
     */
    private function getFields()
    {
        try {
            $result = [];
            foreach ($this->fields as $field) {
                $result[] = '[[' . $field . ']]';
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getFields\n" . $e->getMessage());
        }
        
        if (!empty($result)) {
            return implode(',', $result);
        }
        return '*';
    }
    
    /**
     * Формирует часть запроса к БД, указывающую из какой таблицы берутся данные
     * @return string
     */
    private function getTableName()
    {
        try {
            if (!isset($this->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getTableName\n" . $e->getMessage());
        }
        return ' FROM {{' . $this->tableName . '}}';
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     * @return string
     */
    private function addFilters()
    {
        try {
            $result = [];
            foreach ($this->filterKeys as $filter) {
                if (in_array($filter, array_keys(\Yii::$app->request->get()))) {
                    $result[] = $filter . '=:' . $filter;
                }
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::addFilters\n" . $e->getMessage());
        }
        
        if (!empty($result)) {
            return ((strpos($this->_query, 'WHERE')) ? ' AND ' : ' WHERE ') . implode(' AND ', $result);
        }
        return '';
    }
    
    /**
     * Формирует часть запроса к БД, задающую порядок сортировки
     * @return string
     */
    public function getOrder()
    {
        try {
            if (!isset($this->orderByField)) {
                throw new ErrorException('Не задано имя столбца для сортировки!');
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getOrder\n" . $e->getMessage());
        }
        return ' ORDER BY ' . $this->orderByField . ' ' . $this->orderByRoute;
    }
    
    /**
     * Формирует часть запроса к БД, ограничивающую выборку
     * @return string
     */
    private function getLimit()
    {
        try {
            if (in_array(\Yii::$app->params['pagePointer'], array_keys(\Yii::$app->request->get()))) {
                return ' LIMIT ' . (\Yii::$app->request->get(\Yii::$app->params['pagePointer']) * $this->limit) . ', ' . $this->limit;
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getLimit\n" . $e->getMessage());
        }
        return ' LIMIT 0, ' . $this->limit;
    }
}
