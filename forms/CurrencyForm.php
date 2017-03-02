<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

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
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['code', 'main'],
            self::BASE_CHANGE=>['id', 'main'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['code'], 'required', 'on'=>self::CREATE],
            [['id', 'main'], 'required', 'on'=>self::BASE_CHANGE],
        ];
    }
}
