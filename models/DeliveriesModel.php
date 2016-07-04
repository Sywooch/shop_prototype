<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\DeliveriesMapper;

/**
 * Представляет данные таблицы address
 */
class DeliveriesModel extends AbstractBaseModel
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
    public $price = '';
    
    private $_id = NULL;
    private $_allDeliveries = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['id', 'name', 'description', 'price'],
            self::GET_FROM_DB=>['id', 'name', 'description', 'price'],
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
     * Возвращает массив объектов всех доступных deliveries
     * @return array
     */
    public function getAllDeliveries()
    {
        try {
            if (is_null($this->_allDeliveries)) {
                $deliveriesMapper = new DeliveriesMapper([
                    'tableName'=>'deliveries',
                    'fields'=>['id', 'name', 'description', 'price'],
                ]);
                $deliveriesArray = $deliveriesMapper->getGroup();
                if (!is_array($deliveriesArray) || empty($deliveriesArray)) {
                    return false;
                }
                $this->_allDeliveries = $deliveriesArray;
            }
            return $this->_allDeliveries;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
