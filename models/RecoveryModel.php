<?php

namespace app\models;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;

class RecoveryModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных в сессию
     */
    const SET = 'set';
    
    /**
     * @var string email пользователя, для которого будет изменен пароль
     */
    public $email;
    
    public function scenarios()
    {
        return [
            self::SET=>['email'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email'], 'required'],
        ];
    }
}
