<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\EmailsByCommentsMapper;
use app\mappers\EmailsInsertMapper;
use app\models\EmailsModel;
use yii\base\ErrorException;

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
        if (is_null($this->_id_emails)) {
            $emailsByCommentsMapper = new EmailsByCommentsMapper([
                'tableName'=>'emails',
                'fields'=>['id'],
                'model'=>$this
            ]);
            $emailsModel = $emailsByCommentsMapper->getOne();
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
        return $this->_id_emails;
    }
    
    public function setId_emails($value)
    {
        $this->_id_emails = $value;
    }
}
