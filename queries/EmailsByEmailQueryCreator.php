<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class EmailsByEmailQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $this->_mapperObject->query->where(['emails.email'=>$this->_mapperObject->model->email]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
