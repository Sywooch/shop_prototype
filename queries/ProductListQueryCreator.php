<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\interfaces\VisitorInterface;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductListQueryCreator extends AbstractBaseQueryCreator implements VisitorInterface
{
    /**
     * @var object объект на основании данных которого создается запрос,
     * запрос сохраняется в свойство этого объекта
     */
    private $_mapperObject;
    
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве,
     * запускает процесс
     * @param $object
     */
    public function update($object)
    {
        try {
            $this->_mapperObject = $object;
            $this->getSelectQuery();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::update\n" . $e->getMessage());
        }
    }
    
    /**
     * Инициирует создание запроса, выбирая сценарий на основе данных из объекта запроса Yii::$app->request
     */
    public function getSelectQuery()
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
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::getSelectQuery\n" . $e->getMessage());
        }
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
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::queryForAll\n" . $e->getMessage());
        }
    }
    
    /**
     * Возвращает сформированную строку запроса к БД, фильруя по категории
     * @return string
     */
    public function queryForCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= ' WHERE category=:category';
            $this->addSelectEnd();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::queryForCategory\n" . $e->getMessage());
        }
        $this->_mapperObject->categoryFlag = true;
    }
    
    /**
     * Возвращает сформированную строку запроса к БД, фильруя по подкатегории
     * @return string
     */
    public function queryForSubCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= ' WHERE category=:category AND subcategory=:subcategory';
            $this->addSelectEnd();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::queryForSubCategory\n" . $e->getMessage());
        }
        $this->_mapperObject->categoryFlag = true;
        $this->_mapperObject->subcategoryFlag = true;
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     */
    private function addSelectHead()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->_mapperObject->query .= $this->addTableName();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addSelectHead\n" . $e->getMessage());
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
            foreach ($this->_mapperObject->fields as $field) {
                $result[] = '[[' . $this->_mapperObject->tableName . '.' . $field . ']]';
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addFields\n" . $e->getMessage());
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
            if (!isset($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addTableName\n" . $e->getMessage());
        }
        return ' FROM {{' . $this->_mapperObject->tableName . '}}';
    }
    
    /**
     * Формирует финальную часть строки запроса к БД
     */
    private function addSelectEnd()
    {
        try {
            $this->_mapperObject->query .= $this->addFilters();
            $this->_mapperObject->query .= $this->addOrder();
            $this->_mapperObject->query .= $this->addLimit();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addSelectEnd\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     * @return string
     */
    private function addFilters()
    {
        try {
            $arrayKeys = array_keys(\Yii::$app->request->get());
            $result = [];
            foreach ($this->_mapperObject->filterKeys as $filter) {
                if (in_array($filter, $arrayKeys)) {
                    $result[] = $filter . '=:' . $filter;
                    $this->_mapperObject->filtersArray[':' . $filter] = \Yii::$app->request->get($filter);
                }
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addFilters\n" . $e->getMessage());
        }
        
        $this->_mapperObject->filtersFlag = true;
        
        if (!empty($result)) {
            return ((strpos($this->_mapperObject->query, 'WHERE')) ? ' AND ' : ' WHERE ') . implode(' AND ', $result);
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
            if (!isset($this->_mapperObject->orderByField)) {
                throw new ErrorException('Не задано имя столбца для сортировки!');
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addOrder\n" . $e->getMessage());
        }
        return ' ORDER BY [[' . $this->_mapperObject->tableName . '.' . $this->_mapperObject->orderByField . ']] ' . $this->_mapperObject->orderByRoute;
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
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addLimit\n" . $e->getMessage());
        }
        return ' LIMIT 0, ' . $this->_mapperObject->limit;
    }
}
