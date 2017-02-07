<?php

namespace app\models;

use app\models\{AbstractBaseModel,
    VisitorsCounterInterface};
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы visitors_counter
 */
class VisitorsCounterModel extends AbstractBaseModel implements VisitorsCounterInterface
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
    
    /**
     * Возвращает значение свойства VisitorsCounterModel::counter
     * @return int
     */
    public function getVisits()
    {
        try {
            return $this->counter ?? 0;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
