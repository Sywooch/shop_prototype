<?php

namespace app\models;

use app\models\AbstractFormModel;

/**
 * Представляет данные формы сохранения адреса
 */
class SaveCitiesFormModel extends AbstractFormModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const SAVE = 'save';
    
    public $city;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['city'],
        ];
    }
    
    public function rules()
    {
        return [
            [['city'], 'app\validators\StripTagsValidator'],
            [['city'], 'required', 'on'=>self::SAVE],
        ];
    }
}
