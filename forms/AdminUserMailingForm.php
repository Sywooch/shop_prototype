<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\StripTagsValidator;

/**
 * Представляет данные формы добавления подписки
 */
class AdminUserMailingForm extends AbstractBaseForm
{
    /**
     * Сценарий добавления подписки пользователю из настроек аккаунта
     */
    const SAVE_ACC = 'save_acc';
    /**
     * Сценарий удаление связи пользователя с рассылкой из настроек аккаунта
     */
    const UNSUBSCRIBE_ACC = 'unsubscribe_acc';
    /**
     * Сценарий добавления подписки пользователю из настроек админ панели
     */
    const SAVE_ADMIN = 'save_admin';
    /**
     * Сценарий удаление связи пользователя с рассылкой из настроек админ панели
     */
    const UNSUBSCRIBE_ADMIN= 'unsubscribe_admin';
    
    /**
     * @var int ID пользователя
     */
    public $id_user;
    /**
     * @var string email
     */
    public $email;
    /**
     * @var int ID выбранной подписки
     */
    public $id;
    /**
     * @var string ключ идентификации
     */
    public $key;
    
    public function scenarios()
    {
        return [
            self::UNSUBSCRIBE_ACC=>['id'],
            self::SAVE_ACC=>['id'],
            self::UNSUBSCRIBE_ADMIN=>['id_user', 'id'],
            self::SAVE_ADMIN=>['id_user', 'id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_user', 'email', 'id', 'key'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::SAVE_ACC],
            [['id'], 'required', 'on'=>self::UNSUBSCRIBE_ACC],
            [['id_user', 'id'], 'required', 'on'=>self::SAVE_ADMIN],
            [['id_user', 'id'], 'required', 'on'=>self::UNSUBSCRIBE_ADMIN],
            [['id_user', 'id'], 'integer'],
            [['email'], 'string'],
            [['email'], 'email'],
            [['key'], 'string', 'length'=>40],
        ];
    }
}
