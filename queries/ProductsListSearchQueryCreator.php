<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListSearchQueryCreator extends ProductsListQueryCreator
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
            
            $this->_mapperObject->query .= ' ORDER BY ' . $this->_mapperObject->orderByField . ' ' . $this->_mapperObject->orderByType;
            
            $limit = $this->addLimit();
            if (!$limit) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $limit;
            
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
            if (empty(\Yii::$app->params['sphynxKey'])) {
                throw new ErrorException('Не поределен sphynxKey!');
            }
            
            $getArrayKeys = array_keys(array_filter(\Yii::$app->filters->attributes));
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $filterData = \Yii::$app->filters->$filter;
                    foreach ($filterData as $key=>$val) {
                        $filterKey = $key . $filter . '_' . \Yii::$app->params['idKey'];
                        $this->_mapperObject->params[':' . $filterKey] = $val;
                        $filtersKeys[$filter][] = $filterKey;
                    }
                    $where = $this->getWhereInSphynx(
                        $filter . '_' . \Yii::$app->params['idKey'],
                        implode(',:', $filtersKeys[$filter])
                    );
                    if (!is_string($where)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $where;
                }
            }
            
            if (\Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                if (!is_string($where = $this->getWhereMatchSphynx())) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
                
                $this->_mapperObject->params[':' . \Yii::$app->params['sphynxKey']] = \Yii::$app->request->get(\Yii::$app->params['searchKey']);
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
