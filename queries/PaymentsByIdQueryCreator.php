<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class PaymentsByIdQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'payments'=>[
            'tableName'=>'payments',
            'tableFieldWhere'=>'id',
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $where = $this->getWhere(
                $this->config['payments']['tableName'],
                $this->config['payments']['tableFieldWhere'],
                $this->config['payments']['tableFieldWhere']
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
