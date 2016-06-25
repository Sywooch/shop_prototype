<?php

namespace app\queries;

use yii\base\Object;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\interfaces\VisitorInterface;

/**
 * Абстрактный суперкласс для подклассов, реализующих построение строки запроса к БД
 */
abstract class AbstractBaseQueryCreator extends Object implements VisitorInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object объект на основании данных которого создается запрос,
     * запрос сохраняется в свойство $query этого объекта
     */
    protected $_mapperObject;
    
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве, реализуя VisitorInterface
     * запускает процесс
     * @param $object
     */
    public function update($object)
    {
        try {
            $this->_mapperObject = $object;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
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
     * Формирует часть запроса к БД, добавляя в выборку столбцы данных из JOIN таблиц
     * @return string
     */
    protected function addOtherFields()
    {
        try {
            if (!empty($this->_mapperObject->otherTablesFields)) {
                $result = [];
                foreach ($this->_mapperObject->otherTablesFields as $set) {
                    foreach ($set['fields'] as $field) {
                        $string = '[[' . $set['table'] . '.' . $field['field'] . ']]';
                        if (isset($field['as'])) {
                            $string .= ' AS [['. $field['as'] . ']]';
                        }
                        $result[] = $string;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        if (!empty($result)) {
            return ',' . implode(',', $result);
        }
        return '';
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
        return ' FROM {{' . $this->_mapperObject->tableName . '}}';
    }
    
    /**
     * Формирует часть запроса к БД для INSERT, указывающую из какой таблицы берутся данные
     * @return string
     */
    protected function addTableNameToInsert()
    {
        try {
            if (!isset($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return ' {{' . $this->_mapperObject->tableName . '}}';
    }
    
    /**
     * Формирует часть запроса к БД, объединяющую таблицы
     * @return string
    */
    protected function getJoin($firstTableName, $firstTableFieldOn, $secondTableName, $secondTableFieldOn)
    {
        try {
            return ' JOIN {{' . $secondTableName . '}} ON [[' . $firstTableName . '.' . $firstTableFieldOn . ']]=[[' . $secondTableName . '.' . $secondTableFieldOn . ']]';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую условия выборки WHERE
     * @return string
    */
    protected function getWhere($tableName, $tableField, $key)
    {
        try {
            $string = $this->getWhereStart();
            return $string . ' [[' . $tableName . '.' . $tableField . ']]=:' . $key;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую условия выборки WHERE LIKE
     * @return string
    */
    protected function getWhereLike($tableName, $tableField, $key)
    {
        try {
            $string = $this->getWhereStart();
            return $string . ' [[' . $tableName . '.' . $tableField . ']] LIKE :' . $key;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
        /**
     * Формирует часть запроса к БД, добавляющую условия выборки WHERE IN
     * @return string
    */
    protected function getWhereIn($tableName, $tableField, $key)
    {
        try {
            $string = $this->getWhereStart();
            return $string . ' [[' . $tableName . '.' . $tableField . ']] IN (:' . $key . ')';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую условия выборки WHERE NOT EQUAL
     * @return string
    */
    protected function getWhereNotEqual($tableName, $tableField, $key)
    {
        try {
            $string = $this->getWhereStart();
            return $string . ' [[' . $tableName . '.' . $tableField . ']]!=:' . $key;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую условия выборки WHERE WHERE
     * @return string
    */
    protected function getWhereWhere($tableName, $tableField, $key)
    {
        try {
            return ' WHERE [[' . $tableName . '.' . $tableField . ']]=:' . $key;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, перечисляющие имена столбцов, в которые будут добавлены данные
     * @return string
    */
    protected function addFieldsToInsert()
    {
        try {
            return ' (' . implode(',', $this->_mapperObject->fields) . ')';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, перечисляющие имена столбцов, которые будут обновлены
     * @return string
    */
    protected function addFieldsToUpdate()
    {
        try {
            $result = array();
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Отсутсвуют данные для конструирования запроса!');
            }
            foreach ($this->_mapperObject->fields as $field) {
                $result[] = '[[' . $field . ']]=:' . $field;
            }
            return ' ' . implode(',', $result);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса для подстановки данных
     * @return string
    */
    protected function addValuesToInsert()
    {
        $string = ' VALUES';
        try {
            if (isset($this->_mapperObject->objectsOne)) {
                $string .= ' (:' . implode(',:', $this->_mapperObject->fields) . ')';
            } else {
                throw new ErrorException('Отсутсвуют данные для конструирования запроса!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $string;
    }
    
    /**
     * Формирует часть запроса для подстановки данных
     * @return string
    */
    protected function addGroupValuesToInsert()
    {
        $string = ' VALUES ';
        $arrayValues = array();
        try {
            if (!empty($this->_mapperObject->objectsArray)) {
                foreach ($this->_mapperObject->objectsArray as $keyobject=>$object) {
                    $objectGroup = array();
                    foreach ($this->_mapperObject->fields as $field) {
                        $objectGroup[] = ':' . $keyobject . '_' . $field;
                        $this->_mapperObject->params[':' . $keyobject . '_' . $field] = $object->$field;
                    }
                    $arrayValues[] = '(' . implode(',', $objectGroup) . ')';
                }
            } else {
                throw new ErrorException('Отсутсвуют данные для конструирования запроса!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $string . implode(',', $arrayValues);
    }
    
    protected function getWhereStart()
    {
        $string = strpos($this->_mapperObject->query, 'WHERE') ? ' AND' : ' WHERE';
        return $string;
    }
}
