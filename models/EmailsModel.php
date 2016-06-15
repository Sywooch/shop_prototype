<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\EmailsByEmailMapper;

/**
 * Представляет данные таблицы currency
 */
class EmailsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $email;
    private $_id = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'email'],
            self::GET_FROM_FORM=>['email'],
        ];
    }
    
    public function rules()
    {
        return [
            [['email'], 'required', 'on'=>self::GET_FROM_FORM],
            [['email'], 'email'],
        ];
    }
    
    /**
     * Возвращает значение свойства $this->_id
     */
    public function getId()
    {
        try {
            if (is_null($this->_id)) {
                $emailsByEmailMapper = new EmailsByEmailMapper([
                    'tableName'=>'emails',
                    'fields'=>['id'],
                    'model'=>$this
                ]);
                $emailsModel = $emailsByEmailMapper->getOne();
                $this->_id = $emailsModel->id;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
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
}
