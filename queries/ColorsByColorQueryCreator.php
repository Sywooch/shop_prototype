<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ColorsByColorQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $colorsArrayFilters = [
        'colors'=>[
            'tableName'=>'colors',
            'tableFieldWhere'=>'color',
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
                $this->colorsArrayFilters['colors']['tableName'],
                $this->colorsArrayFilters['colors']['tableFieldWhere'],
                $this->colorsArrayFilters['colors']['tableFieldWhere']
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
