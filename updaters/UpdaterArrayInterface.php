<?php

namespace app\updaters;

/**
 * Интерфейс классов updaters
 */
interface UpdaterArrayInterface
{
    public function update();
    public function setModels(array $models);
}
