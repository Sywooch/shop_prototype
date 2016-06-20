<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class AddressByAddressQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'address'=>[
            'tableName'=>'address', 
            'tableFieldWhereAddress'=>'address', 
            'tableFieldWhereCity'=>'city', 
            'tableFieldWhereCountry'=>'country', 
            'tableFieldWherePostcode'=>'postcode', 
            
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
                $this->categoriesArrayFilters['address']['tableName'],
                $this->categoriesArrayFilters['address']['tableFieldWhereAddress'],
                $this->categoriesArrayFilters['address']['tableFieldWhereAddress']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['address']['tableName'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCity'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCity']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['address']['tableName'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCountry'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCountry']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters['address']['tableName'],
                $this->categoriesArrayFilters['address']['tableFieldWherePostcode'],
                $this->categoriesArrayFilters['address']['tableFieldWherePostcode']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
