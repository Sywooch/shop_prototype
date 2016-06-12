<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupForProductMapper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class CommentsForProductMapper extends AbstractGetGroupForProductMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CommentsForProductQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CommentsObjectsFactory';
    
    public function __construct($config)
    {
        if (!isset($this->paramBindKey)) {
            $this->paramBindKey = 'id_products';
        }
        
        parent::__construct($config);
    }
}
