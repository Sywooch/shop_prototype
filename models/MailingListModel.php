<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы mailing_list
 */
class MailingListModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    /**
     * Сценарий загрузки данных из формы подписки на рассылку для формы регистрации
    */
    const GET_FROM_MAILING_FORM = 'getFromMailingForm';
    /**
     * Сценарий загрузки данных из формы подписки на рассылку для формы подписки
    */
    const GET_FROM_MAILING_FORM_REQUIRE = 'getFromMailingFormRequire';
    
    public $id;
    public $name;
    public $description;
    
    /**
     * @var array массив всех объектов MailingListModel из БД
     */
    private $_allMailingList = null;
    
    /**
     * @var array массив ID, полученный из формы
     */
    public $idFromForm;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'description'],
            self::GET_FROM_MAILING_FORM=>['idFromForm'],
            self::GET_FROM_MAILING_FORM_REQUIRE=>['idFromForm'],
        ];
    }
    
    public function rules()
    {
        return [
            [['idFromForm'], 'required', 'on'=>self::GET_FROM_MAILING_FORM_REQUIRE],
        ];
    }
    
    /**
     * Возвращает массив всех объектов MailingListModel из БД
     * @return array
     */
    public function getAllMailingList()
    {
        try {
            if (is_null($this->_allMailingList)) {
                $this->_allMailingList = MappersHelper::getMailingList();
            }
            return $this->_allMailingList;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив объектов MailingListModel для каждого ID в idFromForm
     * @return array
     */
    public function getObjectsFromIdFromForm()
    {
        try {
            $mailingList = array();
            if (empty($this->idFromForm)) {
                return null;
            }
            foreach ($this->idFromForm as $id) {
                $mailingList[] = MappersHelper::getMailingListById(new self(['id'=>$id]));
            }
            return $mailingList;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
