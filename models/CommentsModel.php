<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\models\EmailsModel;
use yii\base\ErrorException;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы currency
 */
class CommentsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $id;
    public $text;
    public $name;
    public $id_products;
    public $active;
    
    public $email;
    public $categories;
    public $subcategory;
    
    private $_id_emails = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['text', 'name', 'email', 'id_products', 'categories', 'subcategory'],
            self::GET_FROM_DB=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['text', 'email'], 'required', 'on'=>self::GET_FROM_FORM],
            [['email'], 'email'],
            [['text', 'name', 'email'], 'app\validators\StripTagsValidator', 'on'=>self::GET_FROM_FORM],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_id_emails
     * @param int $value
     * @return boolean
     */
    public function setId_emails($value)
    {
        try {
            if (is_numeric($value)) {
                $this->_id_emails = $value;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Если не инициировано свойство $this->_id_emails, выполняет поиск при помощи обращения к БД,
     * если возникает ошибка, добавляет запись в БД и возвращает ID добавленной записи
     * @return int
     */
    public function getId_emails()
    {
        try {
            if (is_null($this->_id_emails)) {
                if (!empty($this->email)) {
                    $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
                    $emailsModel->email = $this->email;
                    $result = MappersHelper::getEmailsByEmail($emailsModel);
                    if (is_object($result) || $result instanceof EmailsModel) {
                        $emailsModel = $result;
                    } else {
                        $result = MappersHelper::setEmailsInsert($emailsModel);
                        if (!$result) {
                            return null;
                        }
                    }
                    $this->_id_emails = $emailsModel->id;
                }
            }
            return $this->_id_emails;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
