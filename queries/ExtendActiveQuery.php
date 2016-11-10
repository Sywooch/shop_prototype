<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\helpers\Formatter;

/**
 * Расширяет класс ActiveQuery
 */
class ExtendActiveQuery extends ActiveQuery
{
    use ExceptionsTrait;
    
    /**
     * @var object yii\data\Pagination
     */
    private $_paginator = null;
    
    /* -------------------------------------------------------------------------------------- */
    
    /**
     * Данные, полученные из БД
     */
    private $_result = null;
    /**
     * Массив настроек для применения форматирования к рузультатам запроса
     */
    private $_formatConfig = [];
    /**
     * Массив настроек для применения метода ArrayHelper::map к рузультату запроса
     */
    private $_afterSortConfig = [];
    
    /**
     * Добавляет ограничения по условиям OFFSET LIMIT
     * @return object ExtendActiveQuery
     */
    public function extendLimit(): ActiveQuery
    {
        try {
            $this->_paginator = new Pagination();
            
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
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет фильтры по цвету, размеру, бренду 
     * @return object ExtendActiveQuery
     */
    public function addFilters(): ActiveQuery
    {
        try {
            if (!empty($keys = array_keys(array_filter(\Yii::$app->filters->toArray())))) {
                if (in_array('colors', $keys)) {
                    $this->innerJoin('{{products_colors}}', '[[products_colors.id_product]]=[[products.id]]');
                    $this->innerJoin('{{colors}}', '[[colors.id]]=[[products_colors.id_color]]');
                    $this->andWhere(['[[colors.id]]'=>\Yii::$app->filters->colors]);
                }
                if (in_array('sizes', $keys)) {
                    $this->innerJoin('{{products_sizes}}', '[[products_sizes.id_product]]=[[products.id]]');
                    $this->innerJoin('{{sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
                    $this->andWhere(['[[sizes.id]]'=>\Yii::$app->filters->sizes]);
                }
                if (in_array('brands', $keys)) {
                    $this->andWhere(['[[products.id_brand]]'=>\Yii::$app->filters->brands]);
                }
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет список полей выборки, дополняя их именем таблицы
     * @return object ExtendActiveQuery
     */
    public function extendSelect(array $fields=[]): ActiveQuery
    {
        try {
            if (!empty($fields)) {
                $fields = array_map(function($value) {
                    return '[[' . $this->modelClass::tableName() . '.' . $value . ']]';
                }, $fields);
                
                $this->select($fields);
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект yii\data\Pagination, 
     * созданный в процессе выполнения метода ExtendActiveQuery::extendLimit
     * @return object
     */
    public function getPaginator(): Pagination
    {
        try {
            return $this->_paginator;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /* -------------------------------------------------------------------------------------- */
    
    /**
     * Сохраняет настройки форматирования, которое будет применено к данным из БД
     */
    public function format(array $config)
    {
        try {
            $this->_formatConfig = $config;
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает тип сортировки результатов выборки
     * @return ActiveQuery
     */
    public function afterSort(): ActiveQuery
    {
        try {
            if (!empty($this->_mapConfig)) {
                $this->_afterSortConfig = ['type'=>'asort'];
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сортирует результаты выборки
     */
    private function runAfterSort()
    {
        try {
            switch ($this->_afterSortConfig['type']) {
                case 'asort':
                    asort($this->_result, SORT_STRING);
                    break;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Расширяет метод ActiveQuery::all
     * @return array
     */
    public function all($db=null): array
    {
        try {
            $this->_result = parent::all($db);
            
            if (!empty($this->_formatConfig)) {
                $this->_result = Formatter::setFormat($this->_result, $this->_formatConfig);
            }
            
            if (!empty($this->_afterSortConfig)) {
                $this->runAfterSort();
            }
            
            return $this->_result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
