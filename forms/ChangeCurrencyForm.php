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
    const CHANGE = 'change';
    
    /**
     * @var int ID валюты, которая будет установлена как текущая
     */
    public $id;
    /**
     * @var string URL, с которого была запрошена сортировка
     */
    public $url;
    
    public function scenarios()
    {
        return [
            self::CHANGE=>['id', 'url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'url'], 'app\validators\StripTagsValidator'],
            [['id', 'url'], 'required', 'on'=>self::CHANGE],
        ];
    }
}
