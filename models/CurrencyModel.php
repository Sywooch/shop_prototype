<?php

namespace app\models;

use yii\db\Transaction;
use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы currency
 */
class CurrencyModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'currency';
}
