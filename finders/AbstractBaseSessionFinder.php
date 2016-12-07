<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\finders\{BaseTrait,
    SessionFinderInterface};
use app\collections\SessionCollectionInterface;

/**
 * Возвращает коллекцию товаров для каталога
 */
abstract class AbstractBaseSessionFinder extends Model implements SessionFinderInterface
{
    use ExceptionsTrait, BaseTrait;
    
    /**
     * @var object SessionCollectionInterface
     */
    protected $collection;
    
    /**
     * Присваивает SessionCollectionInterface свойству static::collection
     * @param object $collection SessionCollectionInterface
     */
    public function setCollection(SessionCollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
