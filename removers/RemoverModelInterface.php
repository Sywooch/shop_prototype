<?php

namespace app\removers;

use yii\base\Model;

/**
 * Интерфейс классов removers
 */
interface RemoverModelInterface
{
    public function remove();
    public function setModel(Model $models);
}
