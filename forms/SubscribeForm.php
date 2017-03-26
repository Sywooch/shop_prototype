<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\StripTagsValidator;

/**
 * Представляет данные формы добавления подписки
 */
class SubscribeForm extends AbstractBaseForm
{
    /**
     * Сценарий добавления подписки пользователю
     */
    const SAVE = 'save';
    
    /**
     * @var string email
     */
    public $email;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['email'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email'], StripTagsValidator::class],
            [['email'], 'required', 'on'=>self::SAVE],
            [['email'], 'email'],
        ];
    }
}
