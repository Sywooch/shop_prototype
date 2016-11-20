<?php

namespace app\models;

/**
 * Интерфейс для применения критериев к запросу
 */
interface CriteriaInterface
{
    public function filter($query);
}
