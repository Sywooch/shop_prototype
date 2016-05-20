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
            if (in_array('category', $getKeys) && !in_array('subcategory', $getKeys)) {
                $this->queryForCategory();
            } else if (in_array('subcategory', $getKeys) && in_array('category', $getKeys)) {
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
            $this->addSelectHead();
            $this->addSelectEnd();
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
            $this->addSelectHead();
            $this->_query .= ' WHERE category=:category';
            $this->addSelectEnd();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::queryForCategory\n" . $e->getMessage());
        }
        return $this->_query;
    }
    
    /**
     * Возвращает сформированную строку запроса к БД, фильруя по подкатегории
     * @return string
     */
    public function queryForSubCategory()
    {
        try {
            $this->addSelectHead();
            $this->_query .= ' WHERE category=:category AND subcategory=:subcategory';
            $this->addSelectEnd();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::queryForSubCategory\n" . $e->getMessage());
        }
        return $this->_query;
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     */
    private function addSelectHead()
    {
        try {
            $this->_query = 'SELECT ';
            $this->_query .= $this->addFields();
            $this->_query .= $this->addTableName();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::addSelectHead\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует финальную часть строки запроса к БД
     */
    private function addSelectEnd()
    {
        try {
            $this->_query .= $this->addFilters();
            $this->_query .= $this->addOrder();
            $this->_query .= $this->addLimit();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::addSelectEnd\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует часть запроса к БД, перечисляющую столбцы данных, которые необходимо включить в выборку
     * @return string
     */
    private function addFields()
    {
        try {
            $result = [];
            foreach ($this->fields as $field) {
                $result[] = '[[' . $field . ']]';
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::addFields\n" . $e->getMessage());
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
    private function addTableName()
    {
        try {
            if (!isset($this->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::addTableName\n" . $e->getMessage());
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
    public function addOrder()
    {
        try {
            if (!isset($this->orderByField)) {
                throw new ErrorException('Не задано имя столбца для сортировки!');
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::addOrder\n" . $e->getMessage());
        }
        return ' ORDER BY ' . $this->orderByField . ' ' . $this->orderByRoute;
    }
    
    /**
     * Формирует часть запроса к БД, ограничивающую выборку
     * @return string
     */
    private function addLimit()
    {
        try {
            if (in_array(\Yii::$app->params['pagePointer'], array_keys(\Yii::$app->request->get()))) {
                return ' LIMIT ' . (\Yii::$app->request->get(\Yii::$app->params['pagePointer']) * $this->limit) . ', ' . $this->limit;
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::addLimit\n" . $e->getMessage());
        }
        return ' LIMIT 0, ' . $this->limit;
    }
}
