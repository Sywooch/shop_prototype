<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListSearchQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $config = [
        'search'=>[ # Данные для выборки из таблицы categories
            'tableName'=>'products', # Имя таблицы участвующей в объединении
            'tableFieldWhere'=>'description', # Имя поля таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
    
    public function init()
    {
        //$parent = (new parent())->categoriesArrayFilters;
        //$this->categoriesArrayFilters = array_merge($parent, $this->categoriesArrayFilters);
        
        $this->categoriesArrayFilters = array_merge($this->categoriesArrayFilters, $this->config);
    }
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->addFilters();
            $this->addSelectEnd();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     */
    protected function addFilters()
    {
        try {
            parent::addFilters();
            
            if (\Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                $this->_mapperObject->query .= $this->getWhereLike(
                    $this->categoriesArrayFilters[\Yii::$app->params['searchKey']]['tableName'],
                    $this->categoriesArrayFilters[\Yii::$app->params['searchKey']]['tableFieldWhere'],
                    \Yii::$app->params['searchKey']
                );
                $this->_mapperObject->params[':' . \Yii::$app->params['searchKey']] = '%' . \Yii::$app->request->get(\Yii::$app->params['searchKey']) . '%';
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
