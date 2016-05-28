<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListSearchQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив для выборки данных с учетом категории или(и) подкатегории, а также фильтров
     */
    public $categoriesArrayFilters = [
        'search'=>[ # Данные для выборки из таблицы categories
            'firstTableName'=>'products', # Имя таблицы участвующей в объединении
            'firstTableFieldWhere'=>'description', # Имя поля таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
    
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
    private function addFilters()
    {
        try {
            $getArrayKeys = array_keys(\Yii::$app->request->get());
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $this->_mapperObject->query .= $this->getWhereLike($filter);
                    $this->_mapperObject->filtersArray[':' . $filter] = \Yii::$app->request->get($filter);
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->filtersFlag = true;
    }
}
