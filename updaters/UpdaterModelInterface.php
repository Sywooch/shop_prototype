<?php

namespace app\updaters;

use yii\base\Model;

/**
 * Интерфейс классов urdaters
 */
interface UpdaterModelInterface
{
    public function update();
    public function setModel(Model $models);
}
