<?php

namespace app\filters;

/**
 * Интерфейс пользовательских фильтров
 */
interface UsersFiltersInterface
{
    public function getSortingField();
    public function getSortingType();
    public function getOrdersStatus();
}
