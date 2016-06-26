<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\EmailsByEmailMapper;
use app\mappers\EmailsInsertMapper;
use app\models\EmailsModel;
use yii\base\ErrorException;

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
    
    private $_id_emails = NULL;
    
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
        ];
    }
    
    /**
     * Если не инициировано свойство $this->_id_emails, выполняет поиск при помощи ображения к БД,
     * если возникает ошибка, добавляет запись в БД и возвращает ID добавленной
     * @return int
     */
    public function getId_emails()
    {
        try {
            if (is_null($this->_id_emails)) {
                $emailsByCommentsMapper = new EmailsByEmailMapper([
                    'tableName'=>'emails',
                    'fields'=>['id'],
                    'model'=>$this
                ]);
                $emailsModel = $emailsByCommentsMapper->getOneFromGroup();
                
                if (!$emailsModel) {
                    if (!isset($this->email)) {
                        throw new ErrorException('Не задан email для создания объекта!');
                    }
                    $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
                    $emailsModel->attributes = ['email'=>$this->email];
                    $emailsInsertMapper = new EmailsInsertMapper([
                        'tableName'=>'emails',
                        'fields'=>['email'],
                        'objectsArray'=>[$emailsModel],
                    ]);
                    $result = $emailsInsertMapper->setGroup();
                    if (!$result) {
                        throw new ErrorException('Не удалось добавить строку в БД!');
                    }
                }
                
                $this->_id_emails = $emailsModel->id;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_id_emails;
    }
    
    /**
     * Присваивает значение свойству $this->_id_emails
     * @param int $value
     */
    public function setId_emails($value)
    {
        $this->_id_emails = $value;
    }
}
