<?php

namespace app\filters;

use yii\base\{ErrorException,
    Model};
use app\filters\AdminProductsFiltersInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class AdminProductsFilters extends Model implements AdminProductsFiltersInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных из сессии
     */
    const SESSION = 'session';
    
    /**
     * @var string имя столбца, покоторому будут отсортированы результаты (date, price, views)
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
    /**
     * @var array массив ID категорий
     */
    private $categories;
    /**
     * @var array массив ID подкатегорий
     */
    private $subcategory;
    /**
     * @var bool
     */
    private $active;
    
    public function scenarios()
    {
        return [
            self::SESSION=>['sortingField', 'sortingType', 'colors', 'sizes', 'brands', 'categories', 'subcategory', 'active']
        ];
    }
    
    /**
     * Присваивает значение AdminProductsFilters::sortingField
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
     * Возвращает значение AdminProductsFilters::sortingField
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
     * Присваивает значение AdminProductsFilters::sortingType
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
     * Возвращает значение AdminProductsFilters::sortingType
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
     * Присваивает значение AdminProductsFilters::colors
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
     * Возвращает значение AdminProductsFilters::colors
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
     * Присваивает значение AdminProductsFilters::sizes
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
     * Возвращает значение AdminProductsFilters::sizes
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
     * Присваивает значение AdminProductsFilters::brands
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
     * Возвращает значение AdminProductsFilters::brands
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
    
    /**
     * Присваивает значение AdminProductsFilters::categories
     * @param array $categories
     */
    public function setCategories($categories)
    {
        try {
            $this->categories = $categories;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение AdminProductsFilters::categories
     * @return mixed
     */
    public function getCategories()
    {
        try {
            return !empty($this->categories) ? $this->categories : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminProductsFilters::subcategory
     * @param array $subcategory
     */
    public function setSubcategory($subcategory)
    {
        try {
            $this->subcategory = $subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение AdminProductsFilters::subcategory
     * @return mixed
     */
    public function getSubcategory()
    {
        try {
            return !empty($this->subcategory) ? $this->subcategory : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminProductsFilters::active
     * @param bool $active
     */
    public function setActive($active)
    {
        try {
            $this->active = $active;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение AdminProductsFilters::active
     * @return mixed
     */
    public function getActive()
    {
        try {
            return !empty($this->active) ? $this->active : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных при вызове AdminProductsFilters::toArray
     * @return array
     */
    public function fields()
    {
        try {
            return [
                'sortingField'=>function() {
                    return $this->sortingField;
                },
                'sortingType'=>function() {
                    return $this->sortingType;
                },
                'colors'=>function() {
                    return $this->colors;
                },
                'sizes'=>function() {
                    return $this->sizes;
                },
                'brands'=>function() {
                    return $this->brands;
                },
                'categories'=>function() {
                    return $this->categories;
                },
                'subcategory'=>function() {
                    return $this->subcategory;
                },
                'active'=>function() {
                    return $this->active;
                },
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
