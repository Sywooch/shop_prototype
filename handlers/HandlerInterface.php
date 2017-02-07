<?php

namespace app\handlers;

/**
 * Интерфейс обработчиков запроса
 */
interface HandlerInterface
{
    public function handle($data);
}
