<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\StripTagsValidator;

/**
 * Представляет данные формы изменения текущей валюты
 */
class ChangeCurrencyForm extends AbstractBaseForm
{
    /**
     * Сценарий изменения текущей валюты
    */
    const SET = 'set';
    
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
            self::SET=>['id', 'url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'url'], StripTagsValidator::class],
            [['id', 'url'], 'required', 'on'=>self::SET],
            [['id'], 'integer'],
            [['url'], 'match', 'pattern'=>'#^/[a-z-0-9/]+$#u'],
        ];
    }
}
