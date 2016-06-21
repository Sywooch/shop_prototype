<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class UsersPhonesByUsersPhonesQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'id_users'=>[
            'tableName'=>'users_phones', 
            'tableFieldWhere'=>'id_users', 
        ],
        'id_phones'=>[
            'tableName'=>'users_phones', 
            'tableFieldWhere'=>'id_phones', 
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
                $this->categoriesArrayFilters['id_phones']['tableName'],
                $this->categoriesArrayFilters['id_phones']['tableFieldWhere'],
                $this->categoriesArrayFilters['id_phones']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
