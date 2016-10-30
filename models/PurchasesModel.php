<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{ColorsModel,
    ProductsModel,
    SizesModel};

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
     * Сценарий удаления 1 товара из корзины
    */
    const GET_FROM_DELETE_FROM_CART = 'getFromDeleteFromCart';
    
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
            self::GET_FROM_DELETE_FROM_CART=>['id_product'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'quantity', 'id_color', 'id_size'], 'required', 'on'=>self::GET_FROM_ADD_TO_CART],
            [['id_product'], 'required', 'on'=>self::GET_FROM_DELETE_FROM_CART],
        ];
    }
    
    /**
     * Получает объект ProductsModel, с которым связан текущий объект PurchasesModel
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
    
    /**
     * Получает объект ColorsModel, с которым связан текущий объект PurchasesModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getColor()
    {
        try {
            return $this->hasOne(ColorsModel::className(), ['id'=>'id_color']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SizesModel, с которым связан текущий объект PurchasesModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getSize()
    {
        try {
            return $this->hasOne(SizesModel::className(), ['id'=>'id_size']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
