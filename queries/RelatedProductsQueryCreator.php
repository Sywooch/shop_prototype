<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\ProductsListQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class RelatedProductsQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'id'=>[
            'firstTableName'=>'products', 
            'firstTableFieldOn'=>'id', 
            'secondTableName'=>'related_products', 
            'secondTableFieldOn'=>'id_related_products', 
            'secondTableFieldWhere'=>'id_products', 
        ],
    ];
    
    public function init()
    {
        try {
            parent::init();
            
            $reflectionParent = new \ReflectionClass('app\queries\ProductsListQueryCreator');
            if ($reflectionParent->hasProperty('config')) {
                $parentConfig = $reflectionParent->getProperty('config')->getValue(new ProductsListQueryCreator);
            }
            $this->config = array_merge($parentConfig, $this->config);
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
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
            }
            
            $this->_mapperObject->query .= 'SELECT ';
            
            if (!$this->addFieldsAndName()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $join = $this->getJoin(
                $this->config[\Yii::$app->params['idKey']]['firstTableName'],
                $this->config[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->config[\Yii::$app->params['idKey']]['secondTableName'],
                $this->config[\Yii::$app->params['idKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            if (!$this->addCategoryAndSubcategory()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $where = $this->getWhere(
                $this->config[\Yii::$app->params['idKey']]['secondTableName'],
                $this->config[\Yii::$app->params['idKey']]['secondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
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
    
    private function addFieldsAndName()
    {
        try {
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
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    private function addCategoryAndSubcategory()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
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
}
