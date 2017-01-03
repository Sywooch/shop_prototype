<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;

class RecoveryModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных в сессию
     */
    const SET = 'set';
    
    /**
     * @var string ключ для ссылки, по которой произойдет смена пароля
     */
    public $key;
    
    public function scenarios()
    {
        return [
            self::SET=>['key'],
        ];
    }
    
    public function rules()
    {
        return [
            [['key'], 'required'],
        ];
    }
}
