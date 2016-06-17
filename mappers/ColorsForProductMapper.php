<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupFromModelMapper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class ColorsForProductMapper extends AbstractGetGroupFromModelMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ColorsForProductQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ColorsObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        if (empty($this->params)) {
            $this->params = [':' . \Yii::$app->params['idKey']=>$this->model->id];
        }
    }
}
