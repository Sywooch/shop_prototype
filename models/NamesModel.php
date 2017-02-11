<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы names
 */
class NamesModel extends AbstractBaseModel
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
            return 'names';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function rules()
    {
        return [
            [['name'], 'required', 'on'=>self::SAVE],
        ];
    }
}
