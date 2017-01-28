<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{AddressModel,
    CitiesModel,
    ColorsModel,
    CountriesModel,
    DeliveriesModel,
    EmailsModel,
    NamesModel,
    PaymentsModel,
    PhonesModel,
    PostcodesModel,
    ProductsModel,
    SizesModel,
    SurnamesModel};

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
     * Сценарий обновления администратором
     */
    const UPDATE_ADMIN = 'update_admin';
    
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
            self::UPDATE_ADMIN=>['id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment', 'received', 'processed', 'canceled', 'shipped'],
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
            [['id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment', 'received', 'processed', 'canceled', 'shipped'], 'required', 'on'=>self::UPDATE_ADMIN],
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
    
    /**
     * Получает объект NamesModel
     * @return ActiveQueryInterface
     */
    public function getName()
    {
        try {
            return $this->hasOne(NamesModel::class, ['id'=>'id_name']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SurnamesModel
     * @return ActiveQueryInterface
     */
    public function getSurname()
    {
        try {
            return $this->hasOne(SurnamesModel::class, ['id'=>'id_surname']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект AddressModel
     * @return ActiveQueryInterface
     */
    public function getAddress()
    {
        try {
            return $this->hasOne(AddressModel::class, ['id'=>'id_address']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект CitiesModel
     * @return ActiveQueryInterface
     */
    public function getCity()
    {
        try {
            return $this->hasOne(CitiesModel::class, ['id'=>'id_city']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект CountriesModel
     * @return ActiveQueryInterface
     */
    public function getCountry()
    {
        try {
            return $this->hasOne(CountriesModel::class, ['id'=>'id_country']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект PostcodesModel
     * @return ActiveQueryInterface
     */
    public function getPostcode()
    {
        try {
            return $this->hasOne(PostcodesModel::class, ['id'=>'id_postcode']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект PhonesModel
     * @return ActiveQueryInterface
     */
    public function getPhone()
    {
        try {
            return $this->hasOne(PhonesModel::class, ['id'=>'id_phone']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект PaymentsModel
     * @return ActiveQueryInterface
     */
    public function getPayment()
    {
        try {
            return $this->hasOne(PaymentsModel::class, ['id'=>'id_payment']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект DeliveriesModel
     * @return ActiveQueryInterface
     */
    public function getDelivery()
    {
        try {
            return $this->hasOne(DeliveriesModel::class, ['id'=>'id_delivery']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект EmailsModel
     * @return ActiveQueryInterface
     */
    public function getEmail()
    {
        try {
            return $this->hasOne(EmailsModel::class, ['id'=>'id_email']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
