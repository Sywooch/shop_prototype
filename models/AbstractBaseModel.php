<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\exceptions\ExceptionsTrait;
use app\queries\ExtendActiveQuery;

abstract class AbstractBaseModel extends ActiveRecord
{
    use ExceptionsTrait;
    
    /**
     * @inheritdoc
     * @return ActiveQuery the newly created ActiveQuery instance
     */
    public static function find()
    {
        try {
            return \Yii::createObject(ExtendActiveQuery::class, [get_called_class()]);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает имя таблицы для экземпляра AR
     * @return string
     */
    public function tableNameObj()
    {
        try {
            return static::tableName();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
