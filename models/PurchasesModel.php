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
     * Сценарий загрузки, выгрузки данных из/в сесии
     */
    const SESSION = 'session';
    /**
     * Сценарий обновления покупки
     */
    const UPDATE = 'update';
    /**
     * Сценарий удаления покупки из корзины
     */
    const DELETE = 'delete';
    /**
     * Сценарий сохранения покупки
     */
    const SAVE = 'save';
    /**
     * Сценарий отмены заказа
     */
    const CANCEL = 'cancel';
    
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
            self::SESSION=>['id_product', 'quantity', 'id_color', 'id_size', 'price'],
            self::UPDATE=>['id_product', 'quantity', 'id_color', 'id_size'],
            self::DELETE=>['id_product'],
            self::SAVE=>['id_user', 'id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'id_product', 'quantity', 'id_color', 'id_size', 'price', 'id_delivery', 'id_payment', 'received', 'received_date'],
            self::CANCEL=>['canceled'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'quantity', 'id_color', 'id_size', 'price'], 'required', 'on'=>self::SESSION],
            [['id_product', 'quantity', 'id_color', 'id_size'], 'required', 'on'=>self::UPDATE],
            [['id_product'], 'required', 'on'=>self::DELETE],
            [['id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'id_product', 'quantity', 'id_color', 'id_size', 'price', 'id_delivery', 'id_payment', 'received', 'received_date'], 'required', 'on'=>self::SAVE],
            [['id_user'], 'default', 'value'=>0, 'on'=>self::SAVE],
        ];
    }
    
    /**
     * Получает объект ProductsModel
     * @return ActiveQueryInterface
     */
    public function getProduct()
    {
        try {
            return $this->hasOne(ProductsModel::class, ['id'=>'id_product']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект ColorsModel
     * @return ActiveQueryInterface
     */
    public function getColor()
    {
        try {
            return $this->hasOne(ColorsModel::class, ['id'=>'id_color']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SizesModel
     * @return ActiveQueryInterface
     */
    public function getSize()
    {
        try {
            return $this->hasOne(SizesModel::class, ['id'=>'id_size']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
