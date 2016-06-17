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
}
