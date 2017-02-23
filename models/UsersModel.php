<?php

namespace app\models;

use yii\web\IdentityInterface;
use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\finders\UserIdFinder;
use app\models\{AddressModel,
    CitiesModel,
    CountriesModel,
    EmailsModel,
    NamesModel,
    PhonesModel,
    PostcodesModel,
    PurchasesModel,
    SurnamesModel};

/**
 * Представляет данные таблицы users
 */
class UsersModel extends AbstractBaseModel implements IdentityInterface
{
    /**
     * Сценарий сохранения нового пользователя
     */
    const SAVE = 'save';
    /**
     * Сценарий обновления данных
     */
    const UPDATE = 'update';
    /**
     * Сценарий обновления пароля
     */
    const UPDATE_PASSW = 'update_passw';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'users';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id_email', 'password', 'id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode'],
            self::UPDATE=>['id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode'],
            self::UPDATE_PASSW=>['password'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_email', 'password'], 'required', 'on'=>self::SAVE],
            [['id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode'], 'default', 'value'=>0, 'on'=>self::SAVE],
            [['id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode'], 'required', 'on'=>self::UPDATE],
            [['password'], 'required', 'on'=>self::UPDATE_PASSW],
        ];
    }
    
    /**
     * Получает объект EmailsModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getEmail()
    {
        try {
            return $this->hasOne(EmailsModel::class, ['id'=>'id_email']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект NamesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
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
     * Получает объект SurnamesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
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
     * Получает объект PhonesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
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
     * Получает объект AddressModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
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
     * Получает объект CitiesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
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
     * Получает объект CountriesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
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
     * Получает объект PostcodesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
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
     * Получает массив объектов PurchasesModel, с которыми связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getOrders()
    {
        try {
            return $this->hasMany(PurchasesModel::class, ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект UsersModel
     * @params int $id 
     * @return mixed UsersModel/false
     */
    public static function findIdentity($id)
    {
        try {
            $finder = new UserIdFinder([
                'id'=>$id,
            ]);
            $result = $finder->find();
            
            return $result;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает ID текущего пользователя
     * @return int
     */
    public function getId(): int
    {
        try {
            return $this->id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public static function findIdentityByAccessToken($token, $type=null)
    {
    }
    
    public function getAuthKey()
    {
    }
    
    public function validateAuthKey($authKey)
    {
    }
}
