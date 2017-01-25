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
            self::SESSION=>['sortingType', 'status']
        ];
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
     * Присваивает значение AdminOrdersFilters::status
     * @param string $status
     */
    public function setStatus($status)
    {
        try {
            $this->status = $status;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение AdminOrdersFilters::status
     * @return mixed
     */
    public function getStatus()
    {
        try {
            return !empty($this->status) ? $this->status : null;
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
                'sortingType'=>function() {
                    return $this->sortingType;
                },
                'status'=>function() {
                    return $this->status;
                },
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
