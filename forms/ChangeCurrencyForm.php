<?php

namespace app\forms;

use yii\base\{ErrorException,
    Model};
use app\forms\{AbstractBaseForm,
    FormInterface};
use app\models\CurrencyModel;

/**
 * Представляет данные формы изменения текущей валюты
 */
class ChangeCurrencyForm extends AbstractBaseForm implements FormInterface
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
