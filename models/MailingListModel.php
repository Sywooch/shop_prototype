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
     * Сценарий загрузки данных из формы подписки на рассылку
    */
    const GET_FROM_MAILING_FORM = 'getFromMailingForm';
    
    public $id;
    public $name;
    public $description;
    
    private $_allMailingList = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'description'],
            self::GET_FROM_MAILING_FORM=>['id'],
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
}
