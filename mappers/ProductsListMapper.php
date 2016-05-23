<?php

namespace app\mappers;

use app\mappers\BaseAbstractMapper;
use yii\base\ErrorException;
use app\queries\ProductListQueryCreator;
use app\factories\ProductObjectsFactory;

/**
 * Получает строки с данными о товарах из БД, конструирует из каждой строки объект данных
 */
class ProductsListMapper extends BaseAbstractMapper
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
     * @var boolean флаг, отмечающий, делается ли выборка для категории
     */
    public $categoryFlag = false;
    /**
     * @var boolean флаг, отмечающий, делается ли выборка для подкатегории
     */
    public $subcategoryFlag = false;
    /**
     * @var boolean флаг, отмечающий, применяются ли фильтры
     */
    public $filtersFlag = false;
    /**
     * @var array массив фильтров для привязки к запросу
     */
    public $filtersArray = array();
    
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
     * Класс ProductListQueryCreator формирует строку запроса и заполняет свойства данными
     * Класс ProductObjectsFactory создает из данных БД объекты
     * @return array
     */
    public function getGroup()
    {
        try {
            $this->visit(new ProductListQueryCreator());
            $this->getData();
            $this->visit(new ProductObjectsFactory());
            print_r($this->query);
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getGroup\n" . $e->getMessage());
        }
        return $this->objectsArray;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    private function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $bindArray = $this->getBindArray();
            if (!empty($bindArray)) {
                $command->bindValues($bindArray);
            }
            $this->DbArray = $command->queryAll();
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
            if ($this->categoryFlag) {
                $result[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->request->get(\Yii::$app->params['categoryKey']);
            } 
            if ($this->subcategoryFlag) {
                $result[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']);
            } 
            if ($this->filtersFlag) {
                $result = array_merge($result, $this->filtersArray);
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListMapper::getBindArray\n" . $e->getMessage());
        }
        return $result;
    }
}
