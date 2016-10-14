<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\exceptions\ExceptionsTrait;
use app\queries\ExtendActiveQuery;

abstract class AbstractBaseModel extends ActiveRecord
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки из БД
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        try {
            return \Yii::createObject(ExtendActiveQuery::className(), [get_called_class()]);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
