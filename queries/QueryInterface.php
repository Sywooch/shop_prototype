<?php

namespace app\queries;

interface QueryInterface
{
    /**
     * Конфигурирует объект запроса для выборки массива строк
     */
    public function getAll();
    /**
     * Конфигурирует объект запроса для выборки одной строки
     */
    public function getOne();
}
