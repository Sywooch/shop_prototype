<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\ProductsListQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListSearchQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'search'=>[
            'tableName'=>'products',
            'tableFieldWhere'=>'id',
        ],
    ];
    
    public function init()
    {
        try {
            parent::init();
            
            $reflectionParent = new \ReflectionClass('app\queries\ProductsListQueryCreator');
            if ($reflectionParent->hasProperty('config')) {
                $parentConfig = $reflectionParent->getProperty('config')->getValue(new ProductsListQueryCreator);
            }
            $this->config = array_merge($parentConfig, $this->config);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!$this->addSelectHead()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->addSelectEnd()) {
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
            
            if (\Yii::$app->request->get(\Yii::$app->params['searchKey']) && !empty($this->_mapperObject->sphynxArray)) {
                if (!parent::addFilters()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                
                $searchID = [];
                foreach ($this->_mapperObject->sphynxArray as $key=>$val) {
                    $data = $key . '_' . $val;
                    $this->_mapperObject->params[':' . $data] = $val;
                    $searchID[] = $data;
                }
                $where = $this->getWhereIn(
                    $this->config[\Yii::$app->params['searchKey']]['tableName'],
                    $this->config[\Yii::$app->params['searchKey']]['tableFieldWhere'],
                    implode(',:', $searchID)
                );
                if (!is_string($where)) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
                
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
