<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет данные, полученные из формы фильтров
 */
class FiltersModel extends Model
{
    /**
     * Сценарий загрузки из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $sortingField = '';
    public $sortingType = '';
    public $colors = [];
    public $sizes = [];
    public $brands = [];
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['sortingField', 'sortingType', 'colors', 'sizes', 'brands'],
        ];
    }
    
    /**
     * Обнуляет значение всех свойств, очищая фильтры
     * @return bool
     */
    public function clean()
    {
        try {
            $this->sortingField = '';
            $this->sortingType = '';
            $this->colors = [];
            $this->sizes = [];
            $this->brands = [];
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
