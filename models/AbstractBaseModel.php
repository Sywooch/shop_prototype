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
    
    /**
     * Возвращает для связанных данных 
     * массив значений указанного свойства модели 
     * @param string $name имя связи
     * @param string $property имя свойства, значения которого будут возвращены
     * @return array
     */
    public function single(string $name, string $property): array
    {
        try {
            $dataArray = $this->$name;
            return !empty($dataArray) ? ArrayHelper::getColumn($dataArray, $property) : [];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
