<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateCurrencyExistsCodeValidator,
    CreateCurrencyValidCodeValidator,
    DeleteCurrencyIsBaseValidator,
    StripTagsValidator};

/**
 * Представляет данные формы редактирования валют
 */
class CurrencyForm extends AbstractBaseForm
{
    /**
     * Сценарий удаления валюты
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания валюты
     */
    const CREATE = 'create';
    /**
     * Сценарий замены базовой валюты
     */
    const BASE_CHANGE = 'base_change';
    
    /**
     * @var int ID валюты
     */
    public $id;
    /**
     * @var string код валюты
     */
    public $code;
    /**
     * @var int флаг, отмечает основную валюту приложения
     */
    public $main;
    /**
     * @var string unicode символ валюты
     */
    public $symbol;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['code', 'main', 'symbol'],
            self::BASE_CHANGE=>['id', 'main'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'code', 'main', 'symbol'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::DELETE],
            [['code', 'symbol'], 'required', 'on'=>self::CREATE],
            [['id', 'main'], 'required', 'on'=>self::BASE_CHANGE],
            [['code'], 'string', 'length'=>3, 'on'=>self::CREATE],
            [['symbol'], 'match', 'pattern'=>'/^[&#0-9;]+$/u', 'on'=>self::CREATE],
            [['id', 'main'], 'integer'],
            [['id'], DeleteCurrencyIsBaseValidator::class, 'on'=>self::DELETE],
            [['code'], CreateCurrencyExistsCodeValidator::class, 'on'=>self::CREATE],
            [['code'], CreateCurrencyValidCodeValidator::class, 'on'=>self::CREATE],
            
        ];
    }
}
