<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class PaymentsByIdQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'payments'=>[ # Данные для выборки из таблицы products
            'tableName'=>'payments', # Имя таблицы участвующей в объединении
            'tableFieldWhere'=>'id', # Имя поля таблицы, по которому делается выборка с помощью WHERE
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
                $this->categoriesArrayFilters['payments']['tableName'],
                $this->categoriesArrayFilters['payments']['tableFieldWhere'],
                $this->categoriesArrayFilters['payments']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
