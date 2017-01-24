<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы visitors_counter
 */
class VisitorsCounterModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных
     */
    const SAVE = 'save';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'visitors_counter';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::SAVE=>['date', 'counter'],
        ];
    }
    
    public function rules()
    {
        return [
            [['date', 'counter'], 'required', 'on'=>self::SAVE],
        ];
    }
}
