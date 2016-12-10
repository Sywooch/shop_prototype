<?php

namespace app\filters;

use yii\base\{ErrorException,
    Model};
use app\filters\ProductsFiltersInterface;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class ProductsFilters extends Model implements ProductsFiltersInterface
{
    /**
     * @var string имя столбца, покоторому будут отсортированы результаты
     */
    public $sortingField;
    /**
     * @var string тип сортировки
     */
    public $sortingType;
    /**
     * @var array массив ID цветов для сортировки
     */
    public $colors;
    /**
     * @var array массив ID размеров для сортировки
     */
    public $sizes;
    /**
     * @var array массив ID брендов для сортировки
     */
    public $brands;
    
    public function rules()
    {
        return [
            [['sortingField', 'sortingType', 'colors', 'sizes', 'brands'], 'safe'],
        ];
    }
    
    /**
     * Возвращает значение ProductsFilters::sortingField
     * @return mixed
     */
    public function getSortingField()
    {
        try {
            return !empty($this->sortingField) ? $this->sortingField : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение ProductsFilters::sortingType
     * @return mixed
     */
    public function getSortingType()
    {
        try {
            return !empty($this->sortingType) ? $this->sortingType : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение ProductsFilters::colors
     * @return mixed
     */
    public function getColors()
    {
        try {
            return !empty($this->colors) ? $this->colors : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение ProductsFilters::sizes
     * @return mixed
     */
    public function getSizes()
    {
        try {
            return !empty($this->sizes) ? $this->sizes : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение ProductsFilters::brands
     * @return mixed
     */
    public function getBrands()
    {
        try {
            return !empty($this->brands) ? $this->brands : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
