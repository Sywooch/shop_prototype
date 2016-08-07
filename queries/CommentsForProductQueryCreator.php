<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class CommentsForProductQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'comments'=>[ # Данные для выборки из таблицы emails
            'tableName'=>'comments', 
            'tableFieldWhere'=>'id_products', 
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
                $this->categoriesArrayFilters['comments']['tableName'],
                $this->categoriesArrayFilters['comments']['tableFieldWhere'],
                $this->categoriesArrayFilters['comments']['tableFieldWhere']
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
