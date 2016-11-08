<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы mailings
 */
class MailingsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы регистрации
    */
    const GET_FROM_REGISTRATION = 'getFromRegistration';
    /**
     * Сценарий сохранения данных из формы регистрации подписчика
    */
    const GET_FROM_ADD_SUBSCRIBER = 'getFromAddSubscriber';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'mailings';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_REGISTRATION=>['id'],
            self::GET_FROM_ADD_SUBSCRIBER=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'description'], 'app\validators\StripTagsValidator'],
            [['id'], 'required', 'on'=>self::GET_FROM_ADD_SUBSCRIBER],
        ];
    }
}
