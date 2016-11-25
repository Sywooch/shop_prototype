<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractFormModel,
    FormInterface};

/**
 * Представляет данные формы изменения текущей валюты
 */
class ChangeCurrencyFormModel extends AbstractFormModel implements FormInterface
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
    
    /**
     * Возвращает объект модели, представляющий таблицу СУБД
     * @return Model
     */
    public function getModel()
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
