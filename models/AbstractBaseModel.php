<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
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
            return \Yii::createObject(ExtendActiveQuery::className(), [get_called_class()]);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
