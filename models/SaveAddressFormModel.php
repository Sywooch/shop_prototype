<?php

namespace app\models;

use app\models\AbstractFormModel;

/**
 * Представляет данные формы сохранения адреса
 */
class SaveAddressFormModel extends AbstractFormModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const SAVE = 'save';
    
    public $address;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['address'],
        ];
    }
    
    public function rules()
    {
        return [
            [['address'], 'app\validators\StripTagsValidator'],
            [['address'], 'required', 'on'=>self::SAVE],
        ];
    }
}
