<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы related_products
 */
class RelatedProductsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных
     */
    const SAVE = 'save';
    /**
     * Сценарий удаления данных
     */
    const DELETE = 'delete';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'related_products';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id_product', 'id_related_product'],
            self::DELETE=>['id_product'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'id_related_product'], 'required', 'on'=>self::SAVE],
            [['id_product'], 'required', 'on'=>self::DELETE],
        ];
    }
}
