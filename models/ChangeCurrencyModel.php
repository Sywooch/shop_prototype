<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractFormModel;

/**
 * Представляет данные формы изменения текущей валюты
 */
class ChangeCurrencyModel extends AbstractFormModel
{
    /**
     * Сценарий изменения текущей валюты
    */
    const CHANGE_CURRENCY = 'changeCurrency';
    
    public $id;
    
    public function scenarios()
    {
        return [
            self::CHANGE_CURRENCY=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::CHANGE_CURRENCY],
        ];
    }
}
