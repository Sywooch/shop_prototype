<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные, полученные из формы фильтров
 */
class FiltersModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $colors = array();
    public $sizes = array();
    public $brands = array();
    public $categories = '';
    public $subcategory = '';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['colors', 'sizes', 'brands', 'categories', 'subcategory'],
        ];
    }
}
