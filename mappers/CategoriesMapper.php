<?php

namespace app\mappers;

use app\mappers\BaseAbstractMapper;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;

class CategoriesMapper extends BaseAbstractMapper
{
    use ExceptionsTrait;
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->orderByRoute)) {
            $this->orderByRoute = \Yii::$app->params['orderByRoute'];
        }
    }
    
    /**
     * Возвращает массив объектов, представляющий строки в БД
     * @return array
     */
    public function getGroup();
    {
        try {
            $this->visit();
            $this->getData();
            $this->visit();
            //print_r($this->query); # выводит строку запроса на экран в отладочных целях
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsArray;
    }
}
