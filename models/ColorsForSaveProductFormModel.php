<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractFormModel,
    FormInterface};

/**
 * Представляет данные формы, получающей список цветов при сохранении товара
 */
class ColorsForSaveProductFormModel extends AbstractFormModel implements FormInterface
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
