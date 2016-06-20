<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class UsersAddressByUsersAddressQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'id_users'=>[
            'tableName'=>'users_address', 
            'tableFieldWhere'=>'id_users', 
        ],
        'id_address'=>[
            'tableName'=>'users_address', 
            'tableFieldWhere'=>'id_address', 
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
                $this->categoriesArrayFilters['id_users']['tableName'],
                $this->categoriesArrayFilters['id_users']['tableFieldWhere'],
                $this->categoriesArrayFilters['id_users']['tableFieldWhere']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['id_address']['tableName'],
                $this->categoriesArrayFilters['id_address']['tableFieldWhere'],
                $this->categoriesArrayFilters['id_address']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
