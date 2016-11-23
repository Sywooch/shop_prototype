<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use app\models\{AbstractBaseCollection,
    CollectionInterface};

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class CurrencyCollection extends AbstractBaseCollection implements CollectionInterface
{
    /**
     * Проверяет существование в коллекции сущности с переданным данными
     * @param object $object Model
     * @return bool
     */
    public function hasEntity(Model $object)
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обновляет данные сущности 
     * @param object $object Model
     * @return bool
     */
    public function update(Model $object)
    {
        try {
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает курс валюты
     * @return float
     */
    public function exchangeRate(): float
    {
        try {
            if (!empty($this->item)) {
                $exchange_rate = $this->item->exchange_rate;
            }
            
            return $exchange_rate ?? 1;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает код валюты
     * @return string
     */
    public function code(): string
    {
        try {
            if (!empty($this->item)) {
                $code = $this->item->code;
            }
            
            return $code ?? '';
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
