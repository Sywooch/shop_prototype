<?php

namespace app\models;

/**
 * Интерфейс получения данных о посещениях
 */
interface VisitorsCounterInterface
{
    public function getVisits();
}
