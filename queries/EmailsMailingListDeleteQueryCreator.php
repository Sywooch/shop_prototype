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
     * Инициирует создание DELETE запроса
     * @return boolean
     */
    public function getDeleteQuery()
    {
        try {
            if (!parent::getDeleteQuery()) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            
            $this->_mapperObject->execute->delete($this->_mapperObject->tableName, [
                'id_email'=>$this->_mapperObject->params['id_email'],
                'id_mailing_list'=>$this->_mapperObject->params['id_mailing_list']
            ]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
