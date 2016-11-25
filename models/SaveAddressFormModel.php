<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractFormModel,
    FormInterface};

/**
 * Представляет данные формы сохранения адреса
 */
class SaveAddressFormModel extends AbstractFormModel implements FormInterface
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
