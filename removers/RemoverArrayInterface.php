<?php

namespace app\removers;

/**
 * Интерфейс классов removers
 */
interface RemoverArrayInterface
{
    public function remove();
    public function setModels(array $models);
}
