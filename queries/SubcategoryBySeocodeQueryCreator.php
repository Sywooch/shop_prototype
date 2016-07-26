<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SubcategoryBySeocodeQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $subcategoryArrayFilters = [
        'subcategory'=>[
            'tableName'=>'subcategory', 
            'tableFieldWhere'=>'seocode', 
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
                $this->subcategoryArrayFilters['subcategory']['tableName'],
                $this->subcategoryArrayFilters['subcategory']['tableFieldWhere'],
                $this->subcategoryArrayFilters['subcategory']['tableFieldWhere']
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
