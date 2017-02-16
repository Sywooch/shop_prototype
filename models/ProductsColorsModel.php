<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы products_colors
 */
class ProductsColorsModel extends AbstractBaseModel
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
            return 'products_colors';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id_product', 'id_color'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'id_color'], 'required', 'on'=>self::SAVE],
        ];
    }
}
