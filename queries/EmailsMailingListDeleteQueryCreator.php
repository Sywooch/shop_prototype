<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractDeleteQueryCreator;

/**
 * Конструирует запрос к БД
 */
class EmailsMailingListDeleteQueryCreator extends AbstractDeleteQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'emails_mailing_list'=>[
            'tableName'=>'emails_mailing_list',
            'tableFieldWhere'=>'id_email',
            'tableFieldWhereTwo'=>'id_mailing_list',
        ],
    ];
    
    /**
     * Инициирует создание DELETE запроса
     * @return boolean
     */
    public function getDeleteQuery()
    {
        try {
            if (!parent::getDeleteQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (empty($this->_mapperObject->objectsArray)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            $id_email_array = array();
            $id_mailing_list_array = array();
            
            foreach ($this->_mapperObject->fields as $field) {
                foreach ($this->_mapperObject->objectsArray as $key=>$object) {
                    $param = $key . '_' . $field;
                    $this->_mapperObject->params[':' . $param] = $object->$field;
                    if ($field == $this->categoriesArrayFilters['emails_mailing_list']['tableFieldWhere']) {
                        $id_email_array[] = $param;
                    } else {
                        $id_mailing_list_array[] = $param;
                    }
                }
            }
            
            $where = $this->getWhereIn(
                $this->categoriesArrayFilters['emails_mailing_list']['tableName'],
                $this->categoriesArrayFilters['emails_mailing_list']['tableFieldWhere'],
                implode(',:', $id_email_array)
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $where = $this->getWhereIn(
                $this->categoriesArrayFilters['emails_mailing_list']['tableName'],
                $this->categoriesArrayFilters['emails_mailing_list']['tableFieldWhereTwo'],
                implode(',:', $id_mailing_list_array)
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
