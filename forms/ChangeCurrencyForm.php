<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы изменения текущей валюты
 */
class ChangeCurrencyForm extends AbstractBaseForm
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
            [['id'], 'app\validators\StripTagsValidator'],
            [['id'], 'required', 'on'=>self::CHANGE_CURRENCY],
        ];
    }
}
