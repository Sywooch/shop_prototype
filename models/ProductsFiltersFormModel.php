<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractFormModel,
    FormInterface};

/**
 * Представляет данные формы изменения текущей валюты
 */
class ProductsFiltersFormModel extends AbstractFormModel implements FormInterface
{
    /**
     * Сценарий сохранения настроек фильтров
    */
    const SAVE = 'save';
    
    public $sortingField = '';
    public $sortingType = '';
    public $colors = [];
    public $sizes = [];
    public $brands = [];
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'colors', 'sizes', 'brands'],
        ];
    }
    
    /**
     * Обнуляет значение всех свойств, очищая фильтры
     */
    public function clean()
    {
        try {
            $this->sortingField = '';
            $this->sortingType = '';
            $this->colors = [];
            $this->sizes = [];
            $this->brands = [];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
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
