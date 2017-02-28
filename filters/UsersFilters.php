<?php

namespace app\filters;

use yii\base\{ErrorException,
    Model};
use app\filters\UsersFiltersInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные формы фильтров для списка пользователей
 */
class UsersFilters extends Model implements UsersFiltersInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных в/из сессии
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
     * @var int статус заказов
     */
    private $ordersStatus;
    
    public function scenarios()
    {
        return [
            self::SESSION=>['sortingField', 'sortingType', 'ordersStatus']
        ];
    }
    
    /**
     * Присваивает значение UsersFilters::sortingField
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
     * Возвращает значение UsersFilters::sortingField
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
     * Присваивает значение UsersFilters::sortingType
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
     * Возвращает значение UsersFilters::sortingType
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
     * Присваивает значение UsersFilters::ordersStatus
     * @param $ordersStatus
     */
    public function setOrdersStatus($ordersStatus)
    {
        try {
            $this->ordersStatus = $ordersStatus;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение UsersFilters::ordersStatus
     * @return mixed
     */
    public function getOrdersStatus()
    {
        try {
            return ($this->ordersStatus === ACTIVE_STATUS || $this->ordersStatus === INACTIVE_STATUS) ? $this->ordersStatus : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных при вызове UsersFilters::toArray
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
                'ordersStatus'=>function() {
                    return $this->ordersStatus;
                },
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
