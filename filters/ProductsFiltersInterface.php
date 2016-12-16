<?php

namespace app\filters;

/**
 * Интерфейс товарных фильров
 */
interface ProductsFiltersInterface
{
    public function getSortingField();
    public function getSortingType();
    public function getColors();
    public function getSizes();
    public function getBrands();
}
