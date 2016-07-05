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
    public $categories = '';
    public $subcategory = '';
    public $search = '';
    
    public function rules()
    {
        return [
            [['colors', 'sizes', 'brands', 'categories', 'subcategory', 'search'], 'safe'],
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
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
