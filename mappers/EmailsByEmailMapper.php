<?php

namespace app\mappers;

use app\mappers\AbstractGetOneByMapper;
use yii\base\ErrorException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class EmailsByEmailMapper extends AbstractGetOneByMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\EmailsByEmailQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\EmailsOneObjectFactory';
    
    public function init()
    {
        parent::init();
        
        $this->params = [':email'=>$this->model->email];
    }
}
