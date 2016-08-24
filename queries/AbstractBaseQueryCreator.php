<?php

namespace app\queries;

use yii\base\{Object,
    ErrorException};
use app\mappers\AbstractBaseMapper;
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
     * @return boolean
     */
    public function update(AbstractBaseMapper $object)
    {
        try {
            $this->_mapperObject = $object;
            return true;
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
            if (empty($this->_mapperObject->fields) || empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
            $result = [];
            foreach ($this->_mapperObject->fields as $field) {
                $result[] = '[[' . $this->_mapperObject->tableName . '.' . $field . ']]';
            }
            if (!empty($result)) {
                return implode(',', $result);
            }
            return '*';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к sphynx, перечисляющую столбцы данных, которые необходимо включить в выборку
     * @return string
     */
    protected function addFieldsSphynx()
    {
        try {
            if (empty($this->_mapperObject->fields) || empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
            return implode(',', $this->_mapperObject->fields);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляя в выборку столбцы данных из JOIN таблиц
     * @return string
     */
    protected function addOtherFields()
    {
        try {
            if (empty($this->_mapperObject->otherTablesFields)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
            $result = [];
            foreach ($this->_mapperObject->otherTablesFields as $set) {
                foreach ($set['fields'] as $field) {
                    $string = '[[' . $set['table'] . '.' . $field['field'] . ']]';
                    if (!empty($field['as'])) {
                        $string .= ' AS [['. $field['as'] . ']]';
                    }
                    $result[] = $string;
                }
            }
            if (!empty($result)) {
                return ',' . implode(',', $result);
            }
            return '';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, указывающую из какой таблицы берутся данные
     * @return string
     */
    protected function addTableName()
    {
        try {
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            return ' FROM {{' . $this->_mapperObject->tableName . '}}';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД для INSERT, указывающую из какой таблицы берутся данные
     * @return string
     */
    protected function addTableNameToInsert()
    {
        try {
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            return ' {{' . $this->_mapperObject->tableName . '}}';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
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
            if (!is_string($string = $this->getWhereStart())) {
                throw new ErrorException('Неверный формат данных!');
            }
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
            if (!is_string($string = $this->getWhereStart())) {
                throw new ErrorException('Неверный формат данных!');
            }
            return $string . ' [[' . $tableName . '.' . $tableField . ']] LIKE :' . $key;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к sphinx, добавляющую условия выборки WHERE MATCH 
     * @return string
    */
    protected function getWhereMatchSphynx()
    {
        try {
            if (empty(\Yii::$app->params['sphynxKey'])) {
                throw new ErrorException('Не поределен sphynxKey!');
            }
            if (!is_string($string = $this->getWhereStart())) {
                throw new ErrorException('Неверный формат данных!');
            }
            return $string . ' MATCH(:' . \Yii::$app->params['sphynxKey'] . ')';
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
            if (!is_string($string = $this->getWhereStart())) {
                throw new ErrorException('Неверный формат данных!');
            }
            return $string . ' [[' . $tableName . '.' . $tableField . ']] IN (:' . $key . ')';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к sphinx, добавляющую условия выборки WHERE IN
     * @return string
    */
    protected function getWhereInSphynx($tableField, $key)
    {
        try {
            if (!is_string($string = $this->getWhereStart())) {
                throw new ErrorException('Неверный формат данных!');
            }
            return $string . ' ' . $tableField . ' IN (:' . $key . ')';
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
            if (!is_string($string = $this->getWhereStart())) {
                throw new ErrorException('Неверный формат данных!');
            }
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
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
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
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
            $result = array();
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
    protected function addGroupValuesToInsert()
    {
        
        try {
            if (empty($this->_mapperObject->objectsArray) || empty($this->_mapperObject->fields)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
            $string = ' VALUES ';
            $arrayValues = array();
            foreach ($this->_mapperObject->objectsArray as $keyobject=>$object) {
                $objectGroup = array();
                foreach ($this->_mapperObject->fields as $field) {
                    $objectGroup[] = ':' . $keyobject . '_' . $field;
                    $this->_mapperObject->params[':' . $keyobject . '_' . $field] = $object->$field;
                }
                $arrayValues[] = '(' . implode(',', $objectGroup) . ')';
            }
            return $string . implode(',', $arrayValues);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса для подстановки данных при появлении дубликатов
     * @return string
    */
    protected function addOnDuplicateKeyUpdate()
    {
        
        try {
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
            $string = ' ON DUPLICATE KEY UPDATE ';
            $arrayValues = array();
            foreach ($this->_mapperObject->fields as $field) {
                if ($field != 'id') {
                    $arrayValues[] = $field . '=VALUES(' . $field . ')';
                }
            }
            return $string . implode(',', $arrayValues);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    protected function getWhereStart()
    {
        try {
            if (empty($this->_mapperObject->query)) {
                throw new ErrorException('Отсутствуют данные для постороения запроса!');
            }
            $string = strpos($this->_mapperObject->query, 'WHERE') ? ' AND' : ' WHERE';
            return $string;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
