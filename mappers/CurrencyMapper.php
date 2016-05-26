<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupMapper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class CurrencyMapper extends AbstractGetGroupMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CurrencyQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CurrencyObjectsFactory';
}
