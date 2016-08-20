<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class AddressByAddressQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
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
            
            if (array_key_exists(':' . $this->config['address']['tableFieldWhereAddress'], $this->_mapperObject->params)) {
                $where = $this->getWhere(
                        $this->config['address']['tableName'],
                    $this->config['address']['tableFieldWhereAddress'],
                    $this->config['address']['tableFieldWhereAddress']
                );
                if (!is_string($where)) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
            }
            
            if (array_key_exists(':' . $this->config['address']['tableFieldWhereCity'], $this->_mapperObject->params)) {
                $where = $this->getWhere(
                    $this->config['address']['tableName'],
                    $this->config['address']['tableFieldWhereCity'],
                    $this->config['address']['tableFieldWhereCity']
                );
                if (!is_string($where)) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
            }
            
            if (array_key_exists(':' . $this->config['address']['tableFieldWhereCountry'], $this->_mapperObject->params)) {
                $where = $this->getWhere(
                    $this->config['address']['tableName'],
                    $this->config['address']['tableFieldWhereCountry'],
                    $this->config['address']['tableFieldWhereCountry']
                );
                if (!is_string($where)) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
            }
            
            if (array_key_exists(':' . $this->config['address']['tableFieldWherePostcode'], $this->_mapperObject->params)) {
                $where = $this->getWhere(
                    $this->config['address']['tableName'],
                    $this->config['address']['tableFieldWherePostcode'],
                    $this->config['address']['tableFieldWherePostcode']
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
