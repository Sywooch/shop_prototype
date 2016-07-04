<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class PaymentsMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\PaymentsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\PaymentsObjectsFactory';
}
