<?php

namespace app\filters;

use yii\base\{ErrorException,
    Model};
use app\filters\AdminOrdersFiltersInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class AdminOrdersFilters extends Model implements AdminOrdersFiltersInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных из сессии
     */
    const SESSION = 'session';
    
    /**
     * @var string имя столбца, покоторому будут отсортированы результаты
     */
    private $sortingField;
    /**
     * @var string тип сортировки
     */
    private $sortingType;
    /**
     * @var string статус заказа
     */
    private $status;
    
    public function scenarios()
    {
        return [
            self::SESSION=>['sortingField', 'sortingType', 'status']
        ];
    }
    
    /**
     * Присваивает значение AdminOrdersFilters::sortingField
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
     * Возвращает значение AdminOrdersFilters::sortingField
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
     * Присваивает значение AdminOrdersFilters::sortingType
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
     * Возвращает значение AdminOrdersFilters::sortingType
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
     * Присваивает значение AdminOrdersFilters::colors
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
     * Возвращает значение AdminOrdersFilters::colors
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
     * Присваивает значение AdminOrdersFilters::sizes
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
     * Возвращает значение AdminOrdersFilters::sizes
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
     * Присваивает значение AdminOrdersFilters::brands
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
     * Возвращает значение AdminOrdersFilters::brands
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
     * Возвращает массив данных при вызове AdminOrdersFilters::toArray
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
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
