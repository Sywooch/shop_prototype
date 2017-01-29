<?php

namespace app\filters;

/**
 * Интерфейс товарных фильров
 */
interface OrdersFiltersInterface
{
    public function getSortingType();
    public function getStatus();
    public function getDatesInterval();
}
