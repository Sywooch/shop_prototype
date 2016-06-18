<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;

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
     */
    public function getSelectQuery()
    {
        try {
            $this->_mapperObject->query .= 'SELECT ';
            $this->addFieldsAndName();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldOn']
            );
            $this->addCategoryAndSubcategory();
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
            $this->_mapperObject->query .= ' UNION SELECT ';
            $this->addFieldsAndName();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['unionSecondTableFieldOn']
            );
            $this->addCategoryAndSubcategory();
            $this->_mapperObject->query .= $this->getWhereWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['unionSecondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    private function addFieldsAndName()
    {
        $this->_mapperObject->query .= $this->addFields();
        $this->_mapperObject->query .= $this->addOtherFields();
        $this->_mapperObject->query .= $this->addTableName();
    }
    
    private function addCategoryAndSubcategory()
    {
        $this->_mapperObject->query .= $this->getJoin(
            $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
            $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
        );
        $this->_mapperObject->query .= $this->getJoin(
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
            $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
        );
    }
}
