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
     * Сценарий загрузки данных из СУБД
     */
    const DBMS = 'dbms';
    
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
            self::DBMS=>['id', 'id_user', 'id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'id_product', 'quantity', 'id_color', 'id_size', 'price', 'id_delivery', 'id_payment', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
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
