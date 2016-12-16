<?php

namespace app\collections;

use app\collections\PaginationInterface;

/**
 * Интерфейс коллекции товаров
 */
interface ProductsCollectionInterface
{
    public function setPagination(PaginationInterface $pagination);
    public function getPagination();
}
