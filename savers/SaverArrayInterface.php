<?php

namespace app\savers;

/**
 * Интерфейс классов savers
 */
interface SaverArrayInterface
{
    public function save();
    public function setModels(array $models);
}
