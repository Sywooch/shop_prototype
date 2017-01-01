<?php

namespace app\savers;

use yii\base\Model;

/**
 * Интерфейс классов savers для сохранения 1 модели
 */
interface SaverModelInterface
{
    public function save();
    public function setModel(Model $model);
}
