<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\PaymentsMapper;

/**
 * Представляет данные таблицы address
 */
class PaymentsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
     */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из формы
     */
    const GET_FROM_DB = 'getFromDb';
    
    public $name = '';
    public $description = '';
    
    private $_id = NULL;
    private $_allPayments = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['id', 'name', 'description'],
            self::GET_FROM_DB=>['id', 'name', 'description'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::GET_FROM_FORM],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_id
     * @param string $value значение ID
     * @return boolean
     */
    public function setId($value)
    {
        try {
            if (is_numeric($value)) {
                $this->_id = $value;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_id
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Возвращает массив объектов всех доступных payments
     * @return array
     */
    public function getAllPayments()
    {
        try {
            if (is_null($this->_allPayments)) {
                $paymentsMapper = new PaymentsMapper([
                    'tableName'=>'payments',
                    'fields'=>['id', 'name', 'description'],
                ]);
                $paymentsArray = $paymentsMapper->getGroup();
                if (!is_array($paymentsArray) || empty($paymentsArray)) {
                    return NULL;
                }
                $this->_allPayments = $paymentsArray;
            }
            return $this->_allPayments;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
