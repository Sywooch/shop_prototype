<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\data\Pagination;
use app\exceptions\ExceptionsTrait;

/**
 * Расширяет класс ActiveQuery
 */
class ExtendActiveQuery extends ActiveQuery
{
    use ExceptionsTrait;
    
    /**
     * @var object объект yii\data\Pagination
     */
    public $paginator;
    
    /**
     * Добавляет ограничения по условиям OFFSET LIMIT
     * @return bool
     */
    public function extendLimit()
    {
        try {
            if (empty($this->paginator)) {
                $this->paginator = new Pagination();
            }
            
            $countQuery = clone $this;
            $page = !empty(\Yii::$app->request->get(\Yii::$app->params['pagePointer'])) ? \Yii::$app->request->get(\Yii::$app->params['pagePointer']) - 1 : 0;
            
            \Yii::configure($this->paginator, [
                'totalCount'=>$countQuery->count(),
                'pageSize'=>\Yii::$app->params['limit'],
                'page'=>$page,
            ]);
            
            if ($this->paginator->page > $this->paginator->pageCount - 1) {
                $this->paginator->page = $this->paginator->pageCount - 1;
            }
            
            $this->offset($this->paginator->offset);
            $this->limit($this->paginator->limit);
            
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
    public function addFilters()
    {
        try {
            if (!empty($keys = array_keys(array_filter(\Yii::$app->filters->attributes)))) {
                $tableName = $this->modelClass::tableName();
                
                foreach (\Yii::$app->params['filterKeys'] as $filter) {
                    if (in_array($filter, $keys)) {
                        $this->innerJoin($tableName . '_' . $filter, $tableName . '.id=' . $tableName . '_' . $filter . '.id_' . substr($tableName, 0, strlen($tableName)-1));
                        $this->innerJoin($filter, $tableName . '_' . $filter . '.id_' . substr($filter, 0, strlen($filter)-1) . '=' . $filter . '.id');
                        $this->andWhere([$filter . '.id'=>\Yii::$app->filters->$filter]);
                    }
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет список полей выборки, дополняя их именем таблицы
     * @return bool
     */
    public function extendSelect(array $fields=[])
    {
        try {
            if (!empty($fields)) {
                $fields = array_map(function($value) {
                    return $this->modelClass::tableName() . '.' . $value;
                }, $fields);
                
                $this->select($fields);
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
