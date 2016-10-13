<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTarit;

/**
 * Представляет данные таблицы brands
 */
class BrandsModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'brands';
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'brand'],
            self::GET_FROM_FORM=>['id', 'brand'],
        ];
    }
}
