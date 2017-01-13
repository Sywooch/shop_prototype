<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы deliveries
 */
class DeliveriesModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'deliveries';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
