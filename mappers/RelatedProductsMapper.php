<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupFromModelMapper;
use yii\base\ErrorException;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class RelatedProductsMapper extends AbstractGetGroupFromModelMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\RelatedProductsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        if (empty($this->params)) {
            $this->params = [':' . \Yii::$app->params['idKey']=>$this->model->id];
        }
    }
}
