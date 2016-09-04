<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет данные, полученные из формы фильтров
 */
class FiltersModel extends Model
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
    
    /**
     * Свойства для фильтрации активных/неактивных товаров в административном разделе
     */
    public $getActive = true;
    public $getNotActive = true;
    
    public function rules()
    {
        return [
            [['colors', 'sizes', 'brands', 'sortingField', 'sortingType', 'categories', 'subcategory', 'search', 'getActive', 'getNotActive'], 'safe'],
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
            $this->sortingField = '';
            $this->sortingType = '';
            $this->getActive = true;
            $this->getNotActive = true;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет значение свойств, необходимых для построения URL
     * @return boolean
     */
    public function cleanOther()
    {
        try {
            $this->categories = '';
            $this->subcategory = '';
            $this->search = '';
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
