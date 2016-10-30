<?php

namespace app\models;

use app\models\{AbstractBaseModel,
    UsersModel};
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы emails
 */
class EmailsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы аутентификации
    */
    const GET_FROM_AUTHENTICATION = 'getFromAuthentication';
    /**
     * Сценарий сохранения данных из формы регистрации
    */
    const GET_FROM_REGISTRATION = 'getFromRegistration';
    /**
     * Сценарий сохранения данных из формы заказа
    */
    const GET_FROM_ORDER = 'getFromOrder';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'emails';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_AUTHENTICATION=>['email'],
            self::GET_FROM_REGISTRATION=>['email'],
            self::GET_FROM_ORDER=>['email'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email'], 'app\validators\StripTagsValidator'],
            [['email'], 'email'],
            [['email'], 'required', 'on'=>self::GET_FROM_AUTHENTICATION],
            [['email'], 'app\validators\EmailExistsAuthValidator', 'on'=>self::GET_FROM_AUTHENTICATION],
            [['email'], 'required', 'on'=>self::GET_FROM_REGISTRATION],
            [['email'], 'app\validators\EmailExistsRegistValidator', 'on'=>self::GET_FROM_REGISTRATION],
            [['email'], 'required', 'on'=>self::GET_FROM_ORDER],
        ];
    }
    
    public function fields()
    {
        return [
            'id'=>'id',
            'email'=>'email',
        ];
    }
    
    /**
     * Получает объект UsersModel, с которым связан текущий объект EmailsModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getUser()
    {
        try {
            return $this->hasOne(UsersModel::className(), ['id_email'=>'id'])->inverseOf('email');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
