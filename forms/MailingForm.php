<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

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
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'email'], 'required', 'on'=>self::SAVE],
            [['email'], 'email', 'on'=>self::SAVE],
        ];
    }
}