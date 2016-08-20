<?php

namespace app\queries;

use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'categories'=>[
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id_categories',
            'secondTableName'=>'categories',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'seocode',
        ],
        'subcategory'=>[
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id_subcategory',
            'secondTableName'=>'subcategory',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'seocode',
        ],
        'products_colors'=>[
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_colors',
            'secondTableFieldOn'=>'id_products',
        ],
        'colors'=>[
            'firstTableName'=>'products_colors',
            'firstTableFieldOn'=>'id_colors',
            'secondTableName'=>'colors',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
        'products_sizes'=>[
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_sizes',
            'secondTableFieldOn'=>'id_products',
        ],
        'sizes'=>[
            'firstTableName'=>'products_sizes',
            'firstTableFieldOn'=>'id_sizes',
            'secondTableName'=>'sizes',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
        'products_brands'=>[
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_brands',
            'secondTableFieldOn'=>'id_products',
        ],
        'brands'=>[
            'firstTableName'=>'products_brands',
            'firstTableFieldOn'=>'id_brands',
            'secondTableName'=>'brands',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            if (!$this->addSelectHead()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (in_array(\Yii::$app->params['categoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            } 
            if (in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForSubCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            }
            
            if (!$this->addSelectEnd()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, фильруя по категории
     * @return boolean
     */
    protected function queryForCategory()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            
            $where = $this->getWhere(
                $this->config[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['categoryKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $this->_mapperObject->params[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->request->get(\Yii::$app->params['categoryKey']);
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, фильруя по подкатегории
     * @return boolean
     */
    protected function queryForSubCategory()
    {
        try {
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            $where = $this->getWhere(
                $this->config[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['subCategoryKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $this->_mapperObject->params[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']);
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     * @return true
     */
    protected function addSelectHead()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            $this->_mapperObject->query = 'SELECT DISTINCT ';
            
            $fields = $this->addFields();
            if (!is_string($fields)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $fields;
            
            $otherFields = $this->addOtherFields();
            if (!is_string($otherFields)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $otherFields;
            
            $name = $this->addTableName();
            if (!is_string($name)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $name;
            
            $join = $this->getJoin(
                $this->config[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            $join = $this->getJoin(
                $this->config[\Yii::$app->params['subCategoryKey']]['firstTableName'],
                $this->config[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
                $this->config[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует финальную часть строки запроса к БД
     * @return boolean
     */
    protected function addSelectEnd()
    {
        try {
            if (!$this->addOrder()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $limit = $this->addLimit();
            if (!$limit) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $limit;
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
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
            }
            
            $getArrayKeys = array_keys(array_filter(\Yii::$app->filters->attributes));
            $filtersKeys = array();
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $join = $this->getJoin(
                        $this->config[$this->_mapperObject->tableName . '_' . $filter]['firstTableName'],
                        $this->config[$this->_mapperObject->tableName . '_' . $filter]['firstTableFieldOn'],
                        $this->config[$this->_mapperObject->tableName . '_' . $filter]['secondTableName'],
                        $this->config[$this->_mapperObject->tableName . '_' . $filter]['secondTableFieldOn']
                    );
                    if (!is_string($join)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $join;
            
                    $join = $this->getJoin(
                        $this->config[$filter]['firstTableName'],
                        $this->config[$filter]['firstTableFieldOn'],
                        $this->config[$filter]['secondTableName'],
                        $this->config[$filter]['secondTableFieldOn']
                    );
                    if (!is_string($join)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $join;
                    
                    $filterData = \Yii::$app->filters->$filter;
                    foreach ($filterData as $key=>$val) {
                        $filterKey = $key . $filter . '_' . \Yii::$app->params['idKey'];
                        $this->_mapperObject->params[':' . $filterKey] = $val;
                        $filtersKeys[$filter][] = $filterKey;
                    }
                }
            }
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $where = $this->getWhereIn(
                        $this->config[$filter]['secondTableName'],
                        $this->config[$filter]['secondTableFieldWhere'],
                        implode(',:', $filtersKeys[$filter])
                    );
                    if (!is_string($where)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $where;
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
            if (empty($this->_mapperObject->orderByField)) {
                throw new ErrorException('Не задано имя столбца для сортировки!');
            }
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            if (empty($this->_mapperObject->orderByType)) {
                throw new ErrorException('Не задан тип сортировки!');
            }
            
            $this->_mapperObject->query .= ' ORDER BY [[' . $this->_mapperObject->tableName . '.' . $this->_mapperObject->orderByField . ']] ' . $this->_mapperObject->orderByType;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, ограничивающую выборку
     * @return string
     */
    protected function addLimit()
    {
        try {
            if (empty(\Yii::$app->params['pagePointer'])) {
                throw new ErrorException('Не поределен pagePointer!');
            }
            if (empty($this->_mapperObject->limit)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            if (in_array(\Yii::$app->params['pagePointer'], array_keys(\Yii::$app->request->get()))) {
                return ' LIMIT ' . (\Yii::$app->request->get(\Yii::$app->params['pagePointer']) * $this->_mapperObject->limit) . ', ' . $this->_mapperObject->limit;
            }
            return ' LIMIT 0, ' . $this->_mapperObject->limit;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
