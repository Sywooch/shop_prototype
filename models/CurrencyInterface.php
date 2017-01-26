<?php

namespace app\models;

/**
 * Интерфейс получения данных текущей валюты
 */
interface CurrencyInterface
{
    public function exchangeRate();
    public function code();
}
