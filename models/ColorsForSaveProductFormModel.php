<?php

namespace app\models;

use app\models\AbstractFormModel;;

/**
 * Представляет данные формы, получающей список цветов при сохранении товара
 */
class ColorsForSaveProductFormModel extends AbstractFormModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const SAVE = 'save';
    
    public $id;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'app\validators\StripTagsValidator'],
            [['id'], 'required', 'on'=>self::SAVE],
        ];
    }
}
