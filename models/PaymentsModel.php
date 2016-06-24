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
    
    public $name;
    public $description;
    
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
     * Возвращает значение свойства $this->_id
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Присваивает значение свойству $this->_id
     * @param string $value значение ID
     */
    public function setId($value)
    {
        $this->_id = $value;
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
                $this->_allPayments = $paymentsMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_allPayments;
    }
}
