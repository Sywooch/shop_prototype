<?php

namespace app\models;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные авторизированного пользователя
 */
class UserIpModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * Сценарий добавления/удаления данных сессии
     */
    const SESSION = 'session';
    
    /**
     * @var string IP адрес
     */
    public $ip;
    
    public function scenarios()
    {
        return [
            self::SESSION=>['ip'],
        ];
    }
    
    public function rules()
    {
        return [
            [['ip'], 'required', 'on'=>self::SESSION],
        ];
    }
}
