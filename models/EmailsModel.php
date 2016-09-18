<?php

namespace app\models;

use app\models\{AbstractBaseModel,
    UsersModel};

/**
 * Представляет данные таблицы emails
 */
class EmailsModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'emails';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'email'],
            self::GET_FROM_FORM=>['id', 'email'],
        ];
    }
    
    /**
     * Получает объект UsersModel, с которым связан текущий объект EmailsModel
     * @return object UsersModel
     */
    public function getUsers()
    {
        try {
            return $this->hasOne(UsersModel::className(), ['id_email'=>'id'])->inverseOf('emails');
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
