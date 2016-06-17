<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class EmailsByEmailMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\EmailsByEmailQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\EmailsObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->model)) {
            throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
        }
        
        if (empty($this->params)) {
            $this->params = [':email'=>$this->model->email];
        }
    }
}
