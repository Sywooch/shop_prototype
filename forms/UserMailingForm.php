<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{IntInArrayValidator,
    MailingsUserExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы добавления подписки
 */
class UserMailingForm extends AbstractBaseForm
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
     * @var int ID пользователя
     */
    public $id_user;
    /**
     * @var string email
     */
    public $email;
    /**
     * @var array ID выбранных подписок
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
        ];
    }
    
    public function rules()
    {
        return [
            [['id_user', 'email', 'id', 'key'], StripTagsValidator::class],
            [['id', 'email'], 'required', 'on'=>self::SAVE],
            [['id', 'email', 'key'], 'required', 'on'=>self::UNSUBSCRIBE],
            [['id_user'], 'integer'],
            [['email'], 'string'],
            [['email'], 'email'],
            [['id'], IntInArrayValidator::class],
            [['key'], 'string', 'length'=>40],
            [['id'], MailingsUserExistsValidator::class, 'on'=>self::SAVE, 'when'=>function($model) {
                return empty($model->errors);
            }],
        ];
    }
}
