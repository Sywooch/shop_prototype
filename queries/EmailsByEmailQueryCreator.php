<?php

namespace app\queries;

use yii\base\ErrorException;
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
