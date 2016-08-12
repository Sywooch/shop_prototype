<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные, полученные из формы фильтров
 */
class FiltersModel extends AbstractBaseModel
{
    public $colors = array();
    public $sizes = array();
    public $brands = array();
    
    public $sortingField;
    public $sortingType;
    
    /**
     * Свойства содержат данные для редиректа после обработки запроса
     */
    public $categories = '';
    public $subcategory = '';
    public $search = '';
    
    public $active = true;
    
    public function rules()
    {
        return [
            [['colors', 'sizes', 'brands', 'sortingField', 'sortingType', 'categories', 'subcategory', 'search', 'active'], 'safe'],
        ];
    }
    
    /**
     * Обнуляет значение всех свойств, очищая фильтры
     * @return boolean
     */
    public function clean()
    {
        try {
            $this->colors = array();
            $this->sizes = array();
            $this->brands = array();
            $this->categories = '';
            $this->subcategory = '';
            $this->search = '';
            $this->active = true;
            $this->sortingField = '';
            $this->sortingType = '';
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
