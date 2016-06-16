<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupParamsMapper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class CommentsForProductMapper extends AbstractGetGroupParamsMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CommentsForProductQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CommentsObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        $this->params = [':id_products'=>$this->model->id];
    }
}
