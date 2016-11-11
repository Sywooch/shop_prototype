<?php

namespace app\models;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные, полученные из формы фильтров
 */
class FiltersModel extends Model
{
    use ExceptionsTrait;
    
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
}
