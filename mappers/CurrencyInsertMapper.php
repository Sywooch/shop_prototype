<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;

/**
 * Добавляет записи в БД
 */
class CurrencyInsertMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CurrencyInsertQueryCreator';
}
