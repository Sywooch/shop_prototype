<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;
use yii\base\ErrorException;

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
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters['address']['tableName'],
                $this->categoriesArrayFilters['address']['tableFieldWhereAddress'],
                $this->categoriesArrayFilters['address']['tableFieldWhereAddress']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters['address']['tableName'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCity'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCity']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters['address']['tableName'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCountry'],
                $this->categoriesArrayFilters['address']['tableFieldWhereCountry']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            if (array_key_exists(':' . $this->categoriesArrayFilters['address']['tableFieldWherePostcode'], $this->_mapperObject->params)) {
                $where = $this->getWhere(
                    $this->categoriesArrayFilters['address']['tableName'],
                    $this->categoriesArrayFilters['address']['tableFieldWherePostcode'],
                    $this->categoriesArrayFilters['address']['tableFieldWherePostcode']
                );
                if (!is_string($where)) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
