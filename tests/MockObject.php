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
    public $baseName;
    public $extension;
    public $tempName;
    public $sphynxArray;
    public $id_email;
    public $id_mailing_list;
    
    public function init()
    {
        parent::init();
        
        if (empty($this->limit)) {
            $this->limit = \Yii::$app->params['limit'];
        }
        
        if (!empty(\Yii::$app->filters->sortingType)) {
            $this->orderByType = \Yii::$app->filters->sortingType;
        } elseif (empty($this->orderByType)) {
            $this->orderByType = \Yii::$app->params['defaultOrderByType'];
        }
        
        if (!empty(\Yii::$app->filters->sortingField)) {
            $this->orderByField = \Yii::$app->filters->sortingField;
        }
    }
    
    public function saveAs($path)
    {
        return true;
    }
}
