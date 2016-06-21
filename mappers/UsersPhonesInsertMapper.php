<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;

/**
 * Добавляет записи в БД
*/
class UsersPhonesInsertMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersPhonesInsertQueryCreator';
    /**
     * @var string имя класса, который создает объекты из переданных данных
     */
    public $objectsClass = 'app\factories\UsersPhonesObjectsFactory';
}
