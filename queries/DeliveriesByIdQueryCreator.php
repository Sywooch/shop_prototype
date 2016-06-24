<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class DeliveriesByIdQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'deliveries'=>[ # Данные для выборки из таблицы products
            'tableName'=>'deliveries', # Имя таблицы участвующей в объединении
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
                $this->categoriesArrayFilters['deliveries']['tableName'],
                $this->categoriesArrayFilters['deliveries']['tableFieldWhere'],
                $this->categoriesArrayFilters['deliveries']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
