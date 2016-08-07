<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsSphynxQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            
            $this->_mapperObject->query .= $this->addFieldsSphynx();
            
            $this->_mapperObject->query .= ' FROM ' . $this->_mapperObject->tableName;
            
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     * @return boolean
     */
    protected function addFilters()
    {
        try {
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не поределен searchKey!');
            }
            
            if (\Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                if (!is_string($where = $this->getWhereMatchSphynx())) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
