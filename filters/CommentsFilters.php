<?php

namespace app\filters;

use yii\base\{ErrorException,
    Model};
use app\filters\CommentsFiltersInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные формы фильтров для списка пользователей
 */
class CommentsFilters extends Model implements CommentsFiltersInterface
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
     * @var int статус
     */
    public $activeStatus;
    
    public function scenarios()
    {
        return [
            self::SESSION=>['sortingField', 'sortingType', 'activeStatus']
        ];
    }
    
    /**
     * Присваивает значение CommentsFilters::sortingField
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
     * Возвращает значение CommentsFilters::sortingField
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
     * Присваивает значение CommentsFilters::sortingType
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
     * Возвращает значение CommentsFilters::sortingType
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
     * Присваивает значение CommentsFilters::activeStatus
     * @param int $activeStatus
     */
    public function setActiveStatus(int $activeStatus)
    {
        try {
            $this->activeStatus = $activeStatus;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение CommentsFilters::activeStatus
     * @return mixed
     */
    public function getActiveStatus()
    {
        try {
            return !empty($this->activeStatus) ? $this->activeStatus : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных при вызове CommentsFilters::toArray
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
                'activeStatus'=>function() {
                    return $this->activeStatus;
                },
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
