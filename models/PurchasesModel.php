<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

/**
 * Представляет данные таблицы purchases
 */
class PurchasesModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы добавления товара в корзину
    */
    const GET_FROM_ADD_TO_CART = 'getFromAddToCart';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'purchases';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ADD_TO_CART=>['id_product', 'quantity', 'id_color', 'id_size'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'quantity', 'id_color', 'id_size'], 'required', 'on'=>self::GET_FROM_ADD_TO_CART],
        ];
    }
    
    /**
     * Получает объект ProductsModel, с которой связан текущий объект PurchasesModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getProduct()
    {
        try {
            return $this->hasOne(ProductsModel::className(), ['id'=>'id_product']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
