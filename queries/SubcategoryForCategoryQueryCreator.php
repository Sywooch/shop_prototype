<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SubcategoryForCategoryQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'categories'=>[
            'firstTableName'=>'subcategory',
            'firstTableFieldOn'=>'id_categories',
            'secondTableName'=>'categories',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
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
            
            $this->_query->innerJoin($this->config['categories']['secondTableName'], $this->config['categories']['firstTableName'] . '.' . $this->config['categories']['firstTableFieldOn'] . '=' . $this->config['categories']['secondTableName'] . '.' . $this->config['categories']['secondTableFieldOn']);
            
            $this->_query->where([$this->config['categories']['secondTableName'] . '.' . $this->config['categories']['secondTableFieldWhere']=>$this->_mapperObject->model->id]);
            
            $this->_mapperObject->query = $this->_query->createCommand()->getRawSql();
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
