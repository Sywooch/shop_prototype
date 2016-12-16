<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\{AbstractBaseCollection,
    ProductsCollectionInterface};

/**
 * Коллекция объектов ProductsModel
 */
class ProductsCollection extends AbstractBaseCollection implements ProductsCollectionInterface
{
    /**
     * @var object PaginationInterface
     */
    private $pagination = null;
    
    /**
     * Сохраняет объект пагинации
     * @param object $pagination PaginationInterface
     */
    public function setPagination(PaginationInterface $pagination)
    {
        try {
            $this->pagination = $pagination;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект пагинации
     * @return PaginationInterface/null
     */
    public function getPagination()
    {
        try {
            return $this->pagination;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
