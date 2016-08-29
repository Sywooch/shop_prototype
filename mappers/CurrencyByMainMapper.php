<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\CurrencyModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class CurrencyByMainMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CurrencyByMainQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CurrencyObjectsFactory';
}
