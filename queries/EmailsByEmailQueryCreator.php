<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class EmailsByEmailQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'emails'=>[ # Данные для выборки из таблицы products
            'tableName'=>'emails', # Имя таблицы участвующей в объединении
            'tableFieldWhere'=>'email', # Имя поля таблицы, по которому делается выборка с помощью WHERE
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
                $this->categoriesArrayFilters['emails']['tableName'],
                $this->categoriesArrayFilters['emails']['tableFieldWhere'],
                $this->categoriesArrayFilters['emails']['tableFieldWhere']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
