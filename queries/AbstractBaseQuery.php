<?php

namespace app\queries;

use yii\base\{ErrorException,
    Object};
use yii\data\Pagination;
use app\traits\ExceptionsTrait;

/**
 * Абстрактный суперкласс построения запроса к БД
 */
abstract class AbstractBaseQuery extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var string имя класса модели
     */
    public $className;
    /**
     * @var string имя таблицы в БД
     */
    public $tableName;
    /**
     * @var array поля для построения запроса
     */
    public $fields;
    /**
     * @var array массив значений сортировки ['field'=>SORT_DESC],
     */
    public $sorting = array();
    /**
     * @var object объект yii\db\ActiveQuery
     */
    public $query;
    /**
     * @var object объект yii\data\Pagination
     */
    public $pagination;
    /**
     * @var array массив значений WHERE для дополнительной фильтрации
     * формат [field=>value,]
     */
    public $extraWhere = array();
    
    public function init()
    {
        parent::init();
        
        if (empty($this->className)) {
            throw new ErrorException('Не определено имя класса!');
        }
        
        $this->query = $this->className::find();
        $this->tableName = $this->className::tableName();
        
        if (empty($this->pagination)) {
            $this->pagination = new Pagination();
        }
    }
    
    /**
     * Добавляет список полей выборки, дополняя их именем таблицы
     * @return bool
     */
    protected function getSelect()
    {
        try {
            if (!empty($this->fields)) {
                $this->fields = array_map(function($value) {
                    return $this->tableName . '.' . $value;
                }, $this->fields);
                
                $this->query->select($this->fields);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет фильтры, указанные в массиве \Yii::$app->params['filterKeys']
     * @return boolean
     */
    protected function addFilters()
    {
        try {
            if (empty(\Yii::$app->params['filterKeys'])) {
                throw new ErrorException('Не определен filterKeys!');
            }
            
            if (!empty($keys = array_keys(array_filter(\Yii::$app->filters->attributes)))) {
                foreach (\Yii::$app->params['filterKeys'] as $filter) {
                    if (in_array($filter, $keys)) {
                        $this->query->innerJoin($this->tableName . '_' . $filter, $this->tableName . '.id=' . $this->tableName . '_' . $filter . '.id_' . $this->tableName);
                        $this->query->innerJoin($filter, $this->tableName . '_' . $filter . '.id_' . $filter . '=' . $filter . '.id');
                        $this->query->andWhere([$filter . '.id'=>\Yii::$app->filters->$filter]);
                    }
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет сортировку ORDERBY
     * @return boolean
     */
    protected function addOrder()
    {
        try {
            if (is_array($this->sorting) && !empty($this->sorting)) {
                foreach ($this->sorting as $field=>$sort) {
                    $this->query->orderBy([$this->tableName . '.' . $field=>$sort]);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет ограничения по условиям OFFSET LIMIT
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
            
            $countQuery = clone $this->query;
            
            \Yii::configure($this->pagination, [
                'totalCount'=>$countQuery->count(),
                'pageSize'=>\Yii::$app->params['limit'],
                'page'=>!empty(\Yii::$app->request->get(\Yii::$app->params['pagePointer'])) ? \Yii::$app->request->get(\Yii::$app->params['pagePointer']) - 1 : 0,
                'pageParam'=>\Yii::$app->params['pagePointer']
            ]);
            
            $this->query->offset($this->pagination->offset);
            $this->query->limit($this->pagination->limit);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет дополнительную фильтрацию по условию WHERE
     * @return boolean
     */
    protected function extraWhere()
    {
        try {
            if (!empty($this->extraWhere)) {
                foreach ($this->extraWhere as $key=>$value) {
                    $this->query->andWhere([$key=>$value]);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
