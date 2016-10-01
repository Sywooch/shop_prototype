<?php

namespace app\models;

use yii\web\IdentityInterface;
use app\models\{AbstractBaseModel,
    EmailsModel};

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
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'users';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address'],
            self::GET_FROM_FORM=>['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address'],
            self::GET_FROM_AUTHENTICATION=>['password'],
            self::GET_FROM_REGISTRATION=>['password'],
        ];
    }
    
    public function rules()
    {
        return [
            [['password'], 'app\validators\StripTagsValidator'],
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
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект UsersModel по $id
     * @params int $id 
     * @return object UsersModel/null
     */
    public static function findIdentity($id)
    {
        try {
            $usersModel = static::findOne($id);
            if ($usersModel instanceof UsersModel) {
                return $usersModel;
            }
            return null;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
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
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
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
