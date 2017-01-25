<?php

namespace app\filters;

/**
 * Интерфейс товарных фильров
 */
interface AdminOrdersFiltersInterface
{
    public function getSortingType();
    public function getStatus();
}
