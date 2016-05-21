<?php

namespace app\mappers;

use app\mappers\BaseAbstractMapper;
use yii\base\ErrorException;
use app\queries\ProductListQueryCreator;

class ProductListMapper extends BaseAbstractMapper
{
    /**
     * @var array массив имен фильтров, которые могут быть переданы в $_GET
     */
    public $filterKeys;
    /**
     * @var int максимальное кол-во возвращаемых записей
     */
    public $limit;
    /**
     * @var string имя таблицы, источника данных
     */
    public $tableName = 'products';
    /**
     * @var boolean флаг, отмечающий, делается ли выборка для категории
     */
    public $_categoryFlag = false;
    /**
     * @var boolean флаг, отмечающий, делается ли выборка для подкатегории
     */
    public $_subcategoryFlag = false;
    /**
     * @var boolean флаг, отмечающий, применяются ли фильтры
     */
    public $_filtersFlag = false;
    /**
     * @var array массив фильтров для привязки к запросу
     */
    public $_filtersArray = array();
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->filterKeys)) {
            $this->filterKeys = \Yii::$app->params['filterKeys'];
        }
        
        if (!isset($this->limit)) {
            $this->limit = \Yii::$app->params['limit'];
        }
        
        if (!isset($this->orderByRoute)) {
            $this->orderByRoute = \Yii::$app->params['orderByRoute'];
        }
    }
    
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getGroup()
    {
        try {
            $this->visit(new ProductListQueryCreator());
            $this->getData();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getGroup\n" . $e->getMessage());
        }
        return $this->_DbArray;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    private function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->_query);
            $bindArray = $this->getBindArray();
            if (!empty($bindArray)) {
                $command->bindValues($bindArray);
            }
            $this->_DbArray = $command->queryAll();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getData\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует агрегированный массив данных для привязки к запросу
     */
    private function getBindArray()
    {
        $result = array();
        try {
            if ($this->_categoryFlag) {
                $result[':category'] = \Yii::$app->request->get('category');
            } 
            if ($this->_subcategoryFlag) {
                $result[':subcategory'] = \Yii::$app->request->get('subcategory');
            } 
            if ($this->_filtersFlag) {
                $result = array_merge($result, $this->_filtersArray);
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getBindArray\n" . $e->getMessage());
        }
        return $result;
    }
}
