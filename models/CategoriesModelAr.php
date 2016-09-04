<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Представляет данные таблицы categories
 */
class CategoriesModelAr extends ActiveRecord
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    private static $_tableName = 'categories';
    
    public function init()
    {
        parent::init();
        
        if (YII_DEBUG) {
            $this->on($this::EVENT_AFTER_FIND, ['app\helpers\FixSentRequests', 'fix']);
        }
    }
    
    public static function tableName()
    {
        try {
            return self::$_tableName;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
