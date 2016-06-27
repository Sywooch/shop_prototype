<?php

namespace app\tests;

use app\mappers\AbstractBaseMapper;

class MockObject extends AbstractBaseMapper
{
    public $query;
    public $tableName;
    public $fields;
    public $params;
    public $DbArray;
    public $objectsArray;
    public $otherTablesFields;
    public $orderByField;
    public $orderByType;
    public $limit;
    public $model;
    
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
