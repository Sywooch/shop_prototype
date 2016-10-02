<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы mailing_list
 */
class MailingListModel extends AbstractBaseModel
{
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
            return 'mailing_list';
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'description'],
            self::GET_FROM_FORM=>['id', 'name', 'description'],
            self::GET_FROM_REGISTRATION=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'description'], 'app\validators\StripTagsValidator'],
        ];
    }
}
