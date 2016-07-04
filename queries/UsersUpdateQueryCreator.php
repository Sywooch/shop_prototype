<?php

namespace app\queries;

use app\queries\AbstractUpdateQueryCreator;
use yii\base\ErrorException;

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
     * @return boolean
     */
    public function getUpdateQuery()
    {
        try {
            if (!parent::getUpdateQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters['users']['tableName'],
                $this->categoriesArrayFilters['users']['tableFieldWhere'],
                $this->categoriesArrayFilters['users']['tableFieldWhere']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
