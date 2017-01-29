<?php

namespace app\filters;

use yii\base\{ErrorException,
    Model};
use app\filters\OrdersFiltersInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class OrdersFilters extends Model implements OrdersFiltersInterface
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
     * Присваивает значение OrdersFilters::sortingType
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
     * Возвращает значение OrdersFilters::sortingType
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
     * Присваивает значение OrdersFilters::status
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
     * Возвращает значение OrdersFilters::status
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
     * Возвращает массив данных при вызове OrdersFilters::toArray
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
