<?php

namespace app\savers;

/**
 * Интерфейс классов savers
 */
interface SaverInterface
{
    public function save();
    public function setModels(array $models);
    public function getModels();
}
