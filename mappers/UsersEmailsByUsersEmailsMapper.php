<?php

namespace app\mappers;

use app\mappers\AbstractGetOneByMapper;
use yii\base\ErrorException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class UsersEmailsByUsersEmailsMapper extends AbstractGetOneByMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersEmailsByUsersEmailsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\UsersEmailsOneObjectFactory';
}
