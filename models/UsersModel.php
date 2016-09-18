<?php

namespace app\models;

use app\models\{AbstractBaseModel,
    EmailsModel};

/**
 * Представляет данные таблицы users
 */
class UsersModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'users';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address'],
            self::GET_FROM_FORM=>['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address'],
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
}
