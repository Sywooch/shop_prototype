<?php

namespace app\mappers;

use app\mappers\AbstractGetOneByMapper;
use yii\base\ErrorException;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class EmailsByCommentsMapper extends AbstractGetOneByMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\EmailsByCommentsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\EmailsOneObjectFactory';
    
    public function init()
    {
        parent::init();
        
        if (!isset($this->paramBindKey)) {
            $this->paramBindKey ='email';
        }
        
        if (!isset($this->paramBindKeyValue)) {
            $this->paramBindKeyValue = $this->model->email;
        }
    }
}
