<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;

/**
 * Получает строки с данными о товарах из БД, конструирует из каждой строки объект данных
 */
class ProductsListMapper extends AbstractGetGroupMapper
{
    /**
     * @var int максимальное кол-во возвращаемых записей
     */
    public $limit;
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductsListQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
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
        
        if (!isset($this->limit)) {
            $this->limit = \Yii::$app->params['limit'];
        }
        
        if (!isset($this->orderByRoute)) {
            $this->orderByRoute = \Yii::$app->params['orderByRoute'];
        }
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $bindArray = $this->getBindArray();
            if (!empty($bindArray)) {
                $command->bindValues($bindArray);
            }
            $this->DbArray = $command->queryAll();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
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
            $this->throwException($e, __METHOD__);
        }
        return $result;
    }
}
