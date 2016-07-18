<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class RelatedProductsQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $config = [
        'id'=>[
            'firstTableName'=>'products', 
            'firstTableFieldOn'=>'id', 
            'secondTableName'=>'related_products', 
            'secondTableFieldOn'=>'id_related_products', 
            'unionSecondTableFieldOn'=>'id_products', 
            'secondTableFieldWhere'=>'id_products', 
            'unionSecondTableFieldWhere'=>'id_related_products', 
        ],
    ];
    
    public function init()
    {
        parent::init();
        
        $this->categoriesArrayFilters = array_merge($this->categoriesArrayFilters, $this->config);
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
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            if (!$this->addCategoryAndSubcategory()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $this->_mapperObject->query .= ' UNION SELECT ';
            
            if (!$this->addFieldsAndName()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $join = $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['unionSecondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            if (!$this->addCategoryAndSubcategory()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $where = $this->getWhereWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['unionSecondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
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
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            $join = $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
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
