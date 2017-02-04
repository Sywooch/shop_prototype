<?php

namespace app\filters;

/**
 * Интерфейс товарных фильров
 */
interface AdminProductsFiltersInterface
{
    public function getSortingField();
    public function getSortingType();
    public function getColors();
    public function getSizes();
    public function getBrands();
    public function getCategory();
    public function getSubcategory();
    public function getActive();
}
