<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет данные, полученные из формы фильтров
 */
class FiltersModel extends Model
{
    public $sortingField;
    public $sortingType;
    public $colors = array();
    public $sizes = array();
    public $brands = array();
    
    public function rules()
    {
        return [
            [['sortingField', 'sortingType', 'colors', 'sizes', 'brands'], 'safe'],
        ];
    }
    
    /**
     * Обнуляет значение всех свойств, очищая фильтры
     * @return boolean
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
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
