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
            self::GET_FROM_DB=>['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address'],
            self::GET_FROM_FORM=>['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address'],
            self::GET_FROM_AUTHENTICATION=>['password'],
            self::GET_FROM_REGISTRATION=>['password', 'name', 'surname', 'id_phone', 'id_address'],
        ];
    }
    
    public function rules()
    {
        return [
            [['password'], 'app\validators\StripTagsValidator'],
            [['name', 'surname'], 'default', 'value'=>''],
            [['id_phone', 'id_address'], 'default', 'value'=>0],
            [['password'], 'required', 'on'=>self::GET_FROM_AUTHENTICATION],
            [['password'], 'required', 'on'=>self::GET_FROM_REGISTRATION],
        ];
    }
    
    /**
     * Получает объект EmailsModel, с которой связан текущий объект UsersModel
     * @return object EmailsModel
     */
    public function getEmails()
    {
        try {
            return $this->hasOne(EmailsModel::className(), ['id'=>'id_email'])->inverseOf('users');
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
    public function getId()
    {
        try {
            return $this->id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }
    
    public function getAuthKey()
    {
    }
    
    public function validateAuthKey($authKey)
    {
    }
}
