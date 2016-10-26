<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы products_brands
 */
class ProductsBrandsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы добавления товара
    */
    const GET_FROM_ADD_PRODUCT = 'getFromAddProduct';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'products_brands';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ADD_PRODUCT=>['id_product', 'id_brand'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'id_brand'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT],
        ];
    }
}
