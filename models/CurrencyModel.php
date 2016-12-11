<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы currency
 */
class CurrencyModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из СУБД
     */
    const DBMS = 'dbms';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'currency';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function rules()
    {
        return [
            [['code'], 'app\validators\StripTagsValidator'],
            [['id', 'code', 'exchange_rate', 'main'], 'safe', 'on'=>self::DBMS],
        ];
    }
}
