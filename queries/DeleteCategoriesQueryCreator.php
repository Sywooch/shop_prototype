<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractDeleteQueryCreator;

/**
 * Конструирует запрос к БД
 */
class DeleteCategoriesQueryCreator extends AbstractDeleteQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'categories'=>[
            'tableName'=>'categories',
            'tableFieldWhere'=>'id',
        ],
    ];
    
    /**
     * Инициирует создание DELETE запроса
     * @return boolean
     */
    public function getDeleteQuery()
    {
        try {
            if (!parent::getDeleteQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (empty($this->_mapperObject->objectsArray)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            $deleteArray = array();
            $property = $this->config['categories']['tableFieldWhere'];
            foreach ($this->_mapperObject->objectsArray as $key=>$object) {
                $param = $key . '_' . $property;
                $this->_mapperObject->params[':' . $param] = $object->$property;
                $deleteArray[] = $param;
            }
            
            $where = $this->getWhereIn(
                $this->config['categories']['tableName'],
                $this->config['categories']['tableFieldWhere'],
                implode(',:', $deleteArray)
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
