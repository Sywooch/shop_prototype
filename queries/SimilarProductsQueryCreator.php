<?php

namespace app\queries;

use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use app\queries\ProductsListQueryCreator;

/**
 * Формирует строку запроса к БД
 */
class SimilarProductsQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'colors'=>[
            'firstTableName'=>'products_colors',
            'firstTableFieldOn'=>'id_colors',
            'secondTableName'=>'colors',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
        'sizes'=>[
            'firstTableName'=>'products_sizes',
            'firstTableFieldOn'=>'id_sizes',
            'secondTableName'=>'sizes',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
    ];
    
    public function init()
    {
        try {
            parent::init();
            
            $reflectionParent = new \ReflectionClass('app\queries\ProductsListQueryCreator');
            if ($reflectionParent->hasProperty('config')) {
                $parentCategoriesArrayFilters = $reflectionParent->getProperty('config')->getValue(new ProductsListQueryCreator);
            }
            $this->config = array_merge($parentCategoriesArrayFilters, $this->config);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание SELECT запроса
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
            if (empty(\Yii::$app->params['filterKeys'])) {
                throw new ErrorException('Не поределен filterKeys!');
            }
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
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
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if ($filter != 'brands') {
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
                }
            }
            $where = $this->getWhereNotEqual(
                $this->config[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableFieldOn'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $where = $this->getWhere(
                $this->config[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                $this->config[\Yii::$app->params['categoryKey']]['secondTableName']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $where = $this->getWhere(
                $this->config[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->config[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
                $this->config[\Yii::$app->params['subCategoryKey']]['secondTableName']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if ($filter != 'brands') {
                    $where = $this->getWhereIn(
                        $this->config[$filter]['secondTableName'],
                        $this->config[$filter]['secondTableFieldWhere'],
                        $filter . implode(",:{$filter}", (array_keys(ArrayHelper::getColumn($this->_mapperObject->model->$filter, \Yii::$app->params['idKey']))))
                    );
                    if (!is_string($where)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $where;
                    if (!$this->addFilter($filter)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                }
            }
            
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
    
    private function addFilter($filter)
    {
        try {
            $array = ArrayHelper::getColumn($this->_mapperObject->model->$filter, \Yii::$app->params['idKey']);
            foreach ($array as $key=>$val) {
                $this->_mapperObject->params[':' . $filter . $key] = $val;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
