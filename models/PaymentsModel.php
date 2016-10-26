<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы payments
 */
class PaymentsModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'payments';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
