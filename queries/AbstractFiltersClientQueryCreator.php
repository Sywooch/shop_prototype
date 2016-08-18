<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractFiltersQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
abstract class AbstractFiltersCLientQueryCreator extends AbstractFiltersQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $config = [
        'active'=>[
            'tableName'=>'products',
            'tableFieldWhere'=>'active',
        ],
    ];
    
    public function init()
    {
        try {
            parent::init();
            $this->categoriesArrayFilters = array_merge($this->categoriesArrayFilters, $this->config);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание SELECT запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!$this->productsJoin()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (in_array(\Yii::$app->params['categoryKey'], array_keys(\Yii::$app->request->get())) && !in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            } elseif (in_array(\Yii::$app->params['categoryKey'], array_keys(\Yii::$app->request->get())) && in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForSubCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            }
            
            if (!$this->activeWhere()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    protected function activeWhere()
    {
        try {
            $where = $this->getWhere(
                $this->categoriesArrayFilters['active']['tableName'],
                $this->categoriesArrayFilters['active']['tableFieldWhere'],
                $this->categoriesArrayFilters['active']['tableFieldWhere']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            $this->_mapperObject->params[':' . $this->categoriesArrayFilters['active']['tableFieldWhere']] = true;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
