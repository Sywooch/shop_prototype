<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;

/**
 * Получает строки с данными о товарах из БД, конструирует из каждой строки объект данных
 */
class ProductsListMapper extends AbstractGetGroupMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductsListQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->limit)) {
            $this->limit = \Yii::$app->params['limit'];
        }
        
        if (!is_null(\Yii::$app->request->get(\Yii::$app->params['orderTypePointer']))) {
            $this->orderByType = \Yii::$app->request->get(\Yii::$app->params['orderTypePointer']);
        } elseif (!isset($this->orderByType)) {
            $this->orderByType = \Yii::$app->params['orderByType'];
        }
        
        if (!is_null(\Yii::$app->request->get(\Yii::$app->params['orderFieldPointer']))) {
            $this->orderByField = \Yii::$app->request->get(\Yii::$app->params['orderFieldPointer']);
        }
    }
}
