<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\EmailsByEmailMapper;
use yii\base\ErrorException;

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
    
    public $email = '';
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
     * @return int
     */
    public function getId()
    {
        try {
            if (is_null($this->_id)) {
                if (empty($this->email)) {
                    throw new ErrorException('Не определены данные для обращения к БД!');
                }
                $emailsByEmailMapper = new EmailsByEmailMapper([
                    'tableName'=>'emails',
                    'fields'=>['id'],
                    'model'=>$this
                ]);
                $emailsModel = $emailsByEmailMapper->getOneFromGroup();
                if (!is_object($emailsModel) || !$emailsModel instanceof $this) {
                    return false;
                }
                $this->_id = $emailsModel->id;
            }
            return $this->_id;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
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
}
