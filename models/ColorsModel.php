<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы colors
 */
class ColorsModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'colors';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
