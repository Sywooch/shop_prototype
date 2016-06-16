<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use yii\base\ErrorException;

/**
 * Добавляет записи в БД
*/
class UsersEmailsInsertMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersEmailsInsertQueryCreator';
    /**
     * @var string имя класса, который создает объекты из переданных данных
     */
    public $objectsClass = 'app\factories\UsersEmailsObjectsFactory';
    
    /**
     * Формирует запрос к БД и выполняет его
     */
    protected function run()
    {
        try {
            if (!isset($this->objectsClass)) {
                throw new ErrorException('Не задано имя класа, формирующего объекты!');
            }
            $this->visit(new $this->objectsClass());
            parent::run();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
