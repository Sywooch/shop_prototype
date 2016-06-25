<?php

namespace app\mappers;

use app\mappers\AbstractUpdateMapper;

/**
 * Добавляет записи в БД
 */
class UsersUpdateMapper extends AbstractUpdateMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersUpdateQueryCreator';
    
    
}
