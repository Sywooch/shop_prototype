<?php

namespace app\queries;

use yii\base\{ErrorException,
    Object};
use app\traits\ExceptionsTrait;

/**
 * Абстрактный суперкласс построения запроса к БД
 */
abstract class AbstractBaseQueryCreator extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var string имя класса модели
     */
    public $className;
    /**
     * @var array массив полей БД, которые необходимо получить
     */
    public $fields;
    /**
     * @var constant тип сортировки
     */
    public $sortingType;
    /**
     * @var string имя поля для сортировки
     */
    public $sortingField;
    /**
     * @var object объект yii\db\ActiveQuery
     */
    protected $_query;
    /**
     * @var string имя таблицы в БД
     */
    protected $_tableName;
    
    public function init()
    {
        parent::init();
        
        if (empty(\Yii::$app->params['defaultSortingType'])) {
            throw new ErrorException('Не поределен defaultSortingType!');
        }
        if (empty($this->className)) {
            throw new ErrorException('Не определено имя класса!');
        }
        
        $this->_query = $this->className::find();
        $this->_tableName = $this->className::tableName();
        
        if (empty($this->sortingType)) {
            $this->sortingType = \Yii::$app->params['defaultSortingType'];
        }
    }
    
    /**
     * Формирует список полей для выборки
     * @return bool
     */
    protected function getSelect()
    {
        try {
            if (!empty($this->fields)) {
                if (!$this->addTableName()) {
                    throw new ErrorException('Ошибка при конструировании имен полей!');
                }
                $this->_query->select($this->fields);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет имя таблицы к имени поля
     * @return bool
     */
    protected function addTableName()
    {
        try {
            if (!empty($this->fields)) {
                $this->fields = array_map(function($value) {
                    return $this->_tableName . '.' . $value;
                }, $this->fields);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     * @return boolean
     */
    protected function addFilters()
    {
        try {
            if (empty(\Yii::$app->params['filterKeys'])) {
                throw new ErrorException('Не поределен filterKeys!');
            }
            
            if (!empty($getArrayKeys = array_keys(array_filter(\Yii::$app->filters->attributes)))) {
                foreach (\Yii::$app->params['filterKeys'] as $filter) {
                    if (in_array($filter, $getArrayKeys)) {
                        $this->_query->innerJoin($this->_tableName . '_' . $filter, $this->_tableName . '.id=' . $this->_tableName . '_' . $filter . '.id_' . $this->_tableName);
                        $this->_query->innerJoin($filter, $this->_tableName . '_' . $filter . '.id_' . $filter . '=' . $filter . '.id');
                        $this->_query->andWhere([$filter . '.id'=>\Yii::$app->filters->$filter]);
                    }
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, задающую порядок сортировки
     * @return boolean
     */
    protected function addOrder()
    {
        try {
            if (!empty($this->sortingField) || !empty(\Yii::$app->filters->sortingField)) {
                $sortingField = \Yii::$app->filters->sortingField ? \Yii::$app->filters->sortingField : $this->sortingField;
                $sortingType = (\Yii::$app->filters->sortingType && \Yii::$app->filters->sortingType == 'SORT_ASC') ? SORT_ASC : $this->sortingType;
                
                $this->_query->orderBy([$this->_tableName . '.' . $sortingField=>$sortingType]);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса orderBy
     * @return boolean
     */
    protected function addLimit()
    {
        try {
            if (empty(\Yii::$app->params['pagePointer'])) {
                throw new ErrorException('Не поределен pagePointer!');
            }
            if (empty(\Yii::$app->params['limit'])) {
                throw new ErrorException('Не поределен limit!');
            }
            
            $this->_query->limit(\Yii::$app->params['limit']);
            
            if (in_array(\Yii::$app->params['pagePointer'], array_keys(\Yii::$app->request->get()))) {
                $this->_query->offset((\Yii::$app->request->get(\Yii::$app->params['pagePointer']) * \Yii::$app->params['limit']) - \Yii::$app->params['limit']);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
