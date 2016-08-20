<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractFiltersQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
abstract class AbstractFiltersClientQueryCreator extends AbstractFiltersQueryCreator
{
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
                $this->config['active']['tableName'],
                $this->config['active']['tableFieldWhere'],
                $this->config['active']['tableFieldWhere']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            $this->_mapperObject->params[':' . $this->config['active']['tableFieldWhere']] = true;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
