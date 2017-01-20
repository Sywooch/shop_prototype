<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы emails_mailings
 */
class EmailsMailingsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения новой подписки для пользователя
     */
    const SAVE = 'save';
    /**
     * Сценарий удаления подписки
     */
    const DELETE = 'delete';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'emails_mailings';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id_email', 'id_mailing'],
            self::DELETE=>['id_email', 'id_mailing'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_email', 'id_mailing'], 'required', 'on'=>self::SAVE],
            [['id_email', 'id_mailing'], 'required', 'on'=>self::DELETE],
        ];
    }
}
