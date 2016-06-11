<?php

namespace app\mappers;

use app\mappers\AbstractGetOneMapper;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class ProductDetailMapper extends AbstractGetOneMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductDetailQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductDetailObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->paramBindKey)) {
            $this->paramBindKey = \Yii::$app->params['idKey'];
        }
        
        if (!isset($this->paramBindKeyValue)) {
            $this->paramBindKeyValue = \Yii::$app->request->get(\Yii::$app->params['idKey']);
        }
    }
}
