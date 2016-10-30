<?php

namespace app\models;

use yii\web\IdentityInterface;
use app\models\{AbstractBaseModel,
    EmailsModel};
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы users
 */
class UsersModel extends AbstractBaseModel implements IdentityInterface
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
            return 'users';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_AUTHENTICATION=>['password'],
            self::GET_FROM_REGISTRATION=>['password', 'name', 'surname', 'id_phone', 'id_address'],
            self::GET_FROM_ORDER=>['name', 'surname'],
        ];
    }
    
    public function rules()
    {
        return [
            [['password', 'name', 'surname'], 'app\validators\StripTagsValidator'],
            [['name', 'surname'], 'default', 'value'=>''],
            [['id_phone', 'id_address'], 'default', 'value'=>0],
            [['password'], 'required', 'on'=>self::GET_FROM_AUTHENTICATION],
            [['password'], 'required', 'on'=>self::GET_FROM_REGISTRATION],
            [['name', 'surname'], 'required', 'on'=>self::GET_FROM_ORDER],
        ];
    }
    
    public function fields()
    {
        return [
            'id_email'=>'id_email',
            'name'=>'name',
            'surname'=>'surname',
            'id_phone'=>'id_phone',
            'id_address'=>'id_address'
        ];
    }
    
    /**
     * Получает объект EmailsModel, с которой связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getEmail()
    {
        try {
            return $this->hasOne(EmailsModel::className(), ['id'=>'id_email'])->inverseOf('user');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект UsersModel по $id
     * @params int $id 
     * @return mixed UsersModel/null
     */
    public static function findIdentity($id)
    {
        try {
            $usersModel = static::findOne($id);
            if ($usersModel instanceof UsersModel) {
                return $usersModel;
            }
            
            return null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
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
