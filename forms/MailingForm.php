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
     * @var string email
     */
    public $email;
    /**
     * @var int ID выбранных подписок
     */
    public $id = [];
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id', 'email'],
            self::UNSUBSCRIBE=>['id', 'email'],
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
            [['id', 'email'], 'required', 'on'=>self::UNSUBSCRIBE],
            [['email'], 'email', 'on'=>self::UNSUBSCRIBE],
        ];
    }
}
