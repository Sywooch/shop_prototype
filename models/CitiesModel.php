<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы cities
 */
class CitiesModel extends AbstractBaseModel
{
    /**
     * Сценарий вставки записи
     */
    const SAVE = 'save';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'cities';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function rules()
    {
        return [
            [['city'], 'required', 'on'=>self::SAVE],
        ];
    }
}
