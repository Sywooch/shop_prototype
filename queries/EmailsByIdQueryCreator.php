<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class EmailsByIdQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'emails'=>[ 
            'tableName'=>'emails', 
            'tableFieldWhere'=>'id', 
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
                $this->categoriesArrayFilters['emails']['tableName'],
                $this->categoriesArrayFilters['emails']['tableFieldWhere'],
                $this->categoriesArrayFilters['emails']['tableFieldWhere']
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
