<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\MailingsUserExistsValidator;

/**
 * Представляет данные формы добавления подписки
 */
class MailingForm extends AbstractBaseForm
{
    /**
     * Сценарий добавления подписки пользователю
     */
    const SAVE = 'save';
    /**
     * Сценарий удаление связи пользователя с рассылками
     */
    const UNSUBSCRIBE = 'unsubscribe';
    /**
     * Сценарий удаление связи пользователя с рассылкой из настроек аккаунта
     */
    const UNSUBSCRIBE_ACC = 'unsubscribe_acc';
    /**
     * Сценарий добавления подписки пользователю из настроек аккаунта
     */
    const SAVE_ACC = 'save_acc';
    
    /**
     * @var string email
     */
    public $email;
    /**
     * @var int ID выбранных подписок
     */
    public $id;
    /**
     * @var string ключ идентификации
     */
    public $key;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id', 'email'],
            self::UNSUBSCRIBE=>['id', 'email', 'key'],
            self::UNSUBSCRIBE_ACC=>['id'],
            self::SAVE_ACC=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'email'], 'required', 'on'=>self::SAVE],
            [['email'], 'email', 'on'=>self::SAVE],
            [['id'], MailingsUserExistsValidator::class, 'on'=>self::SAVE, 'when'=>function($model) {
                return empty($model->errors);
            }],
            [['id', 'email', 'key'], 'required', 'on'=>self::UNSUBSCRIBE],
            [['email'], 'email', 'on'=>self::UNSUBSCRIBE],
            [['id'], 'required', 'on'=>self::UNSUBSCRIBE_ACC],
            [['id'], 'required', 'on'=>self::SAVE_ACC],
        ];
    }
}
