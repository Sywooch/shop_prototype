<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;
use yii\base\ErrorException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class UsersEmailsByUsersEmailsMapper extends AbstractGetGroupMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersEmailsByUsersEmailsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\UsersEmailsObjectsFactory';
}
