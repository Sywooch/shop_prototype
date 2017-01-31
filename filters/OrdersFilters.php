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
     * Сценарий загрузки данных в/из сессии
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
    /**
     * @var int Unix Timestamp
     */
    private $dateFrom;
    /**
     * @var int Unix Timestamp
     */
    private $dateTo;
    
    public function scenarios()
    {
        return [
            self::SESSION=>['sortingType', 'status', 'dateFrom', 'dateTo']
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
    public function setStatus(string $status)
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
     * Присваивает значение OrdersFilters::dateFrom
     * @param int $dateFrom
     */
    public function setDateFrom(int $dateFrom)
    {
        try {
            if (preg_match('/^[0-9]{10}$/', $dateFrom) !== 1) {
                throw new ErrorException($this->invalidError('dateFrom'));
            }
            
            $this->dateFrom = $dateFrom;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение OrdersFilters::dateFrom
     * @return mixed
     */
    public function getDateFrom()
    {
        try {
            return !empty($this->dateFrom) ? $this->dateFrom : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение OrdersFilters::dateTo
     * @param int $dateTo
     */
    public function setDateTo(int $dateTo)
    {
        try {
            if (preg_match('/^[0-9]{10}$/', $dateTo) !== 1) {
                throw new ErrorException($this->invalidError('dateTo'));
            }
            
            $this->dateTo = $dateTo;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение OrdersFilters::dateTo
     * @return mixed
     */
    public function getDateTo()
    {
        try {
            return !empty($this->dateTo) ? $this->dateTo : null;
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
                'dateFrom'=>function() {
                    return $this->dateFrom;
                },
                'dateTo'=>function() {
                    return $this->dateTo;
                },
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
