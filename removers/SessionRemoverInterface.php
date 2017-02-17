<?php

namespace app\removers;

/**
 * Интерфейс сессионных ремуверов
 */
interface SessionRemoverInterface
{
    public function remove();
    public function setKeys(array $keys);
}
