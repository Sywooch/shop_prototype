<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractFiltersQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
abstract class AbstractFiltersAdminQueryCreator extends AbstractFiltersQueryCreator
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
            
            if (!empty(\Yii::$app->filters->categories) && empty(\Yii::$app->filters->subcategory)) {
                if (!$this->queryForCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            } elseif (!empty(\Yii::$app->filters->categories) && !empty(\Yii::$app->filters->subcategory)) {
                if (!$this->queryForSubCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, если указана категория
     * @return boolean
     */
    protected function queryForCategory()
    {
        try {
            if (!$this->productsJoin()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!parent::queryForCategory()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, если указана подкатегория
     * @return boolean
     */
    protected function queryForSubCategory()
    {
        try {
            if (!$this->productsJoin()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!parent::queryForSubCategory()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
