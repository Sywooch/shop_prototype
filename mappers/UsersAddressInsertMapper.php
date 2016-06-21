<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;

/**
 * Добавляет записи в БД
*/
class UsersAddressInsertMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersAddressInsertQueryCreator';
    /**
     * @var string имя класса, который создает объекты из переданных данных
     */
    public $objectsClass = 'app\factories\UsersAddressObjectsFactory';
}
