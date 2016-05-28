<?php

namespace app\queries;

use yii\base\Object;
use app\traits\ExceptionsTrait;

/**
 * Абстрактный суперкласс для подклассов, реализующих построение строки запроса к БД
 */
abstract class AbstractBaseQueryCreator extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var object объект на основании данных которого создается запрос,
     * запрос сохраняется в свойство $query этого объекта
     */
    protected $_mapperObject;
    
    /**
     * Формирует часть запроса к БД, перечисляющую столбцы данных, которые необходимо включить в выборку
     * @return string
     */
    protected function addFields()
    {
        try {
            $result = [];
            foreach ($this->_mapperObject->fields as $field) {
                $result[] = '[[' . $this->_mapperObject->tableName . '.' . $field . ']]';
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
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
    protected function addTableName()
    {
        try {
            if (!isset($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->query .= ' FROM {{' . $this->_mapperObject->tableName . '}}';
    }
    
    /**
     * Формирует часть запроса к БД, объединяющую таблицы
     * @return string
    */
    protected function getJoin($key)
    {
        try {
            return ' JOIN {{' . $this->categoriesArrayFilters[$key]['secondTableName'] . '}} ON [[' . $this->categoriesArrayFilters[$key]['firstTableName'] . '.' . $this->categoriesArrayFilters[$key]['firstTableFieldOn'] . ']]=[[' . $this->categoriesArrayFilters[$key]['secondTableName'] . '.' . $this->categoriesArrayFilters[$key]['secondTableFieldOn'] . ']]';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую условия выборки WHERE
     * @return string
    */
    protected function getWhere($key)
    {
        try {
            $string = strpos($this->_mapperObject->query, 'WHERE') ? ' AND' : ' WHERE';
            return $string . ' [[' . $this->categoriesArrayFilters[$key]['secondTableName'] . '.' . $this->categoriesArrayFilters[$key]['secondTableFieldWhere'] . ']]=:' . $key;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую условия выборки WHERE LIKE
     * @return string
    */
    protected function getWhereLike($key)
    {
        try {
            $string = strpos($this->_mapperObject->query, 'WHERE') ? ' AND' : ' WHERE';
            return $string . ' [[' . $this->categoriesArrayFilters[$key]['firstTableName'] . '.' . $this->categoriesArrayFilters[$key]['firstTableFieldWhere'] . ']] LIKE %:' . $key . '%';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание SELECT запроса
     */
    abstract public function getSelectQuery();
}
