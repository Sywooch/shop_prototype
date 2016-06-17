<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupFromModelMapper;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class UsersByLoginMapper extends AbstractGetGroupFromModelMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersByLoginQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\UsersObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        if (empty($this->params)) {
            $this->params = [':login'=>$this->model->login];
        }
    }
}
