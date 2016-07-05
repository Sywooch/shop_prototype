<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы users
 */
class UsersPurchasesModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий сохранения данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $id = '';
    public $id_users = '';
    public $id_products = '';
    public $id_colors = '';
    public $id_sizes = '';
    public $quantity = '';
    public $id_deliveries = '';
    public $id_payments = '';
    
    private $_received = 0;
    private $_received_date = NULL;
    private $_processed = 0;
    private $_canceled = 0;
    private $_shipped = 0;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['id_users', 'id_products', 'id_colors', 'id_sizes', 'quantity', 'id_deliveries', 'id_payments'],
            self::GET_FROM_DB=>['id', 'id_users', 'id_products', 'id_colors', 'id_sizes', 'quantity', 'id_deliveries', 'id_payments', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_received
     * @param string $value значение received
     * @return boolean
     */
    public function setReceived($value)
    {
        try {
            if ($value) {
                $this->_received = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_received
     * @return int
     */
    public function getReceived()
    {
        try {
            if ($this->scenario == UsersPurchasesModel::GET_FROM_FORM) {
                $this->_received = 1;
            }
            return $this->_received;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_received_date
     * @param string $value значение received_date
     * @return boolean
     */
    public function setReceived_date($value)
    {
        try {
            $this->_received_date = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_received_date
     * @return int
     */
    public function getReceived_date()
    {
        try {
            if (is_null($this->_received_date)) {
                if ($this->scenario == UsersPurchasesModel::GET_FROM_FORM) {
                    $this->_received_date = time();
                }
            }
            return $this->_received_date;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_processed
     * @param string $value значение processed
     * @return boolean
     */
    public function setProcessed($value)
    {
        try {
            if ($value) {
                $this->_processed = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_processed
     * @return int
     */
    public function getProcessed()
    {
        try {
            return $this->_processed;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_canceled
     * @param string $value значение canceled
     * @return boolean
     */
    public function setCanceled($value)
    {
        try {
            if ($value) {
                $this->_canceled = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_canceled
     * @return int
     */
    public function getCanceled()
    {
        try {
            return $this->_canceled;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_shipped
     * @param string $value значение shipped
     * @return boolean
     */
    public function setShipped($value)
    {
        try {
            if ($value) {
                $this->_shipped = 1;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_shipped
     * @return int
     */
    public function getShipped()
    {
        try {
            return $this->_shipped;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}