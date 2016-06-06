<?php

namespace app\mappers;

use app\mappers\AbstractGroupInsertMapper;

/**
 * Добавляет записи в БД
*/
class UsersRulesInsertMapper extends AbstractGroupInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersRulesInsertQueryCreator';
}
