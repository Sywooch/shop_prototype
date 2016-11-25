<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractFormModel,
    FormInterface};

/**
 * Представляет данные формы сохранения адреса
 */
class SaveCitiesFormModel extends AbstractFormModel implements FormInterface
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
    
    /**
     * Возвращает объект модели, представляющий таблицу СУБД
     * @return Model
     */
    public function getModel($name)
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
