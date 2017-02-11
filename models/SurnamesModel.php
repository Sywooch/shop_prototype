<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы surnames
 */
class SurnamesModel extends AbstractBaseModel
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
            return 'surnames';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function rules()
    {
        return [
            [['surname'], 'required', 'on'=>self::SAVE],
        ];
    }
}
