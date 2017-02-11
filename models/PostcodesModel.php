<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы postcodes
 */
class PostcodesModel extends AbstractBaseModel
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
            return 'postcodes';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function rules()
    {
        return [
            [['postcode'], 'required', 'on'=>self::SAVE],
        ];
    }
}
