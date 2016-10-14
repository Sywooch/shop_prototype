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
    public $colors = array();
    public $sizes = array();
    public $brands = array();
    
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
            $this->colors = array();
            $this->sizes = array();
            $this->brands = array();
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
