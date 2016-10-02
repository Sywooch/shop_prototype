<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы sizes
 */
class SizesModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'sizes';
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'size'],
            self::GET_FROM_FORM=>['id', 'size'],
        ];
    }
}
