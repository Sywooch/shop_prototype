<?php

namespace app\queries;

use app\queries\AbstractUpdateQueryCreator;

/**
 * Конструирует запрос к БД
 */
class UsersUpdateQueryCreator extends AbstractUpdateQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'users'=>[
            'tableName'=>'users', 
            'tableFieldWhere'=>'id',
        ],
    ];
    
    /**
     * Инициирует создание UPDATE запроса
     */
    public function getUpdateQuery()
    {
        try {
            parent::getUpdateQuery();
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['users']['tableName'],
                $this->categoriesArrayFilters['users']['tableFieldWhere'],
                $this->categoriesArrayFilters['users']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
