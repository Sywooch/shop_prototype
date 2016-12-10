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
    private $sortingField;
    /**
     * @var string тип сортировки
     */
    private $sortingType;
    /**
     * @var array массив ID цветов для сортировки
     */
    private $colors;
    /**
     * @var array массив ID размеров для сортировки
     */
    private $sizes;
    /**
     * @var array массив ID брендов для сортировки
     */
    private $brands;
    
    public function rules()
    {
        return [
            [['sortingField', 'sortingType', 'colors', 'sizes', 'brands'], 'safe'],
        ];
    }
    
    /**
     * Присваивает значение ProductsFilters::sortingField
     * @param string $sortingField
     */
    public function setSortingField(string $sortingField)
    {
        try {
            $this->sortingField = $sortingField;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
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
     * Присваивает значение ProductsFilters::sortingType
     * @param string $sortingType
     */
    public function setSortingType(string $sortingType)
    {
        try {
            $this->sortingType = $sortingType;
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
     * Присваивает значение ProductsFilters::colors
     * @param mixed $colors
     */
    public function setColors($colors)
    {
        try {
            $this->colors = $colors;
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
     * Присваивает значение ProductsFilters::sizes
     * @param mixed $colors
     */
    public function setSizes($sizes)
    {
        try {
            $this->sizes = $sizes;
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
     * Присваивает значение ProductsFilters::brands
     * @param mixed $brands
     */
    public function setBrands($brands)
    {
        try {
            $this->brands = $brands;
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
