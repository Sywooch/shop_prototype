<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\queries\ARWrapper;

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
     * Получает данные из СУБД в формате массива, форматирует полученный массив, 
     * представляя выбранные столбцы в формате пар ключ-значение, 
     * где одно из полей станет ключем, а второе значением
     * @params string $fieldKey поле, которое станет ключем
     * @params string $fieldValue поле, которое станет значением
     * @return array
     */
    public function allMap(string $fieldKey, string $fieldValue): array
    {
        try {
            $this->asArray();
            $resultArray = $this->all();
            
            return !empty($resultArray) ? ArrayHelper::map($resultArray, $fieldKey, $fieldValue) : [];
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
    
    /**
     * Получает группу строк из СУБД в формате массивов, оборачивает полученные данные 
     * объектом app\queries\ARWrapper, обеспечивающим доступ к данным как к обычным свойствам ActiveRecord 
     * полезно для быстрого переключения типа получаемых данных без изменений клиентского кода
     * @return array
     */
    public function allArray(): array
    {
        try {
            $this->asArray();
            $rawResult = $this->all();
            
            $result = ARWrapper::set($rawResult);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает 1 строку из СУБД в формате массива, оборачивает полученные данные 
     * объектом app\queries\ARWrapper, обеспечивающим доступ к данным как к обычным свойствам ActiveRecord 
     * полезно для быстрого переключения типа получаемых данных без изменений клиентского кода
     * @return mixed
     */
    public function oneArray()
    {
        try {
            $this->asArray();
            $rawResult = $this->one();
            
            $result = ARWrapper::setOne($rawResult);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
