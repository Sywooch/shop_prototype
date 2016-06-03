<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupForProductMapper;
use yii\base\ErrorException;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class RelatedProductsMapper extends AbstractGetGroupForProductMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\RelatedProductsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
}
