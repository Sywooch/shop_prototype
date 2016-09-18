<?php

namespace app\traits;

/**
 * Коллекция свойств и методов для Query классов
 */
trait QueryTrait
{
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
     * @var object yii\db\ActiveQuery, yii\sphinx\Query
     */
    public $query;
    /**
     * @var object объект yii\data\Pagination
     */
    public $paginator;
    /**
     * @var array массив значений WHERE для дополнительной фильтрации
     * формат [field=>value,]
     */
    public $extraWhere = array();
    
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
     * Фильтр опирается на соглашение, что имена таблиц, связывающих таблицы по 
     * принципу М2М состоят из имен отдельных таблиц, обединенных нижним подчеркиванием
     * Например, именем М2М таблицы для products и brands будет products_brands
     * Поля, сылающиеся на связанные таблицы носят имена id_product, id_brand
     * @return boolean
     */
    protected function addFilters()
    {
        try {
            if (empty(\Yii::$app->params['filterKeys'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$app->params[\'filterKeys\']']));
            }
            
            if (!empty($keys = array_keys(array_filter(\Yii::$app->filters->attributes)))) {
                foreach (\Yii::$app->params['filterKeys'] as $filter) {
                    if (in_array($filter, $keys)) {
                        $this->query->innerJoin($this->tableName . '_' . $filter, $this->tableName . '.id=' . $this->tableName . '_' . $filter . '.id_' . substr($this->tableName, 0, strlen($this->tableName)-1));
                        $this->query->innerJoin($filter, $this->tableName . '_' . $filter . '.id_' . substr($filter, 0, strlen($filter)-1) . '=' . $filter . '.id');
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
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$app->params[\'pagePointer\']']));
            }
            if (empty(\Yii::$app->params['limit'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$app->params[\'limit\']']));
            }
            
            $countQuery = clone $this->query;
            $page = !empty(\Yii::$app->request->get(\Yii::$app->params['pagePointer'])) ? \Yii::$app->request->get(\Yii::$app->params['pagePointer']) - 1 : 0;
            
            \Yii::configure($this->paginator, [
                'totalCount'=>$countQuery->count(),
                'pageSize'=>\Yii::$app->params['limit'],
                'page'=>$page,
            ]);
            
            if ($this->paginator->page > $this->paginator->pageCount - 1) {
                $this->paginator->page = $this->paginator->pageCount - 1;
            }
            
            $this->query->offset($this->paginator->offset);
            $this->query->limit($this->paginator->limit);
            
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
