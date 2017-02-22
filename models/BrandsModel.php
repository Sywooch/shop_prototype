<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы brands
 */
class BrandsModel extends AbstractBaseModel
{
    /**
     * Сценарий удаления категории
     */
    const DELETE = 'delete';
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
        ];
    }
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'brands';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
