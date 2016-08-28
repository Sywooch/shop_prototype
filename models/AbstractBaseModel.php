<?php

namespace app\models;

use yii\base\Model;
use app\traits\ExceptionsTrait;

abstract class AbstractBaseModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * Возвращает имя таблицы, представляемой текущей моделью
     * @return string
     */
    public static function getTableName()
    {
        try {
            $splitName = array_filter(preg_split('/(?=[A-Z])/', static::className()));
            $sliceName = array_slice($splitName, 1, -1);
            $tableName = mb_strtolower(implode('_', $sliceName));
            return !empty($tableName) ? $tableName : null;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
