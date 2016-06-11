<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class CommentsForProductQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'comments'=>[ # Данные для выборки из таблицы emails
            'tableName'=>'comments', 
            'tableFieldWhere'=>'id_products', 
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            parent::getSelectQuery();
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['comments']['tableName'],
                $this->categoriesArrayFilters['comments']['tableFieldWhere'],
                $this->categoriesArrayFilters['comments']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
