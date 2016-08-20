<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsBrandsByIdBrandsQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'products_brands'=>[
            'tableName'=>'products_brands',
            'tableFieldWhere'=>'id_brands',
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
                $this->config['products_brands']['tableName'],
                $this->config['products_brands']['tableFieldWhere'],
                $this->config['products_brands']['tableFieldWhere']
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
