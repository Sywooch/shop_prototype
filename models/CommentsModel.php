<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы currency
 */
class CommentsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $id;
    public $text;
    public $name;
    public $active;
    
    public $email;
    
    private $_id_emails = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['text', 'name', 'email'],
        ];
    }
    
    public function rules()
    {
        return [
            [['text', 'email'], 'required', 'on'=>self::GET_FROM_FORM],
            [['email'], 'email'],
        ];
    }
    
    public function getId_emails()
    {
        return $this->_id_emails;
    }
    
    public function setId_emails($value)
    {
        $this->_id_emails = $value;
    }
}
