<?php

namespace app\queries;

use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use app\queries\ProductsListQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListAdminQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'products'=>[
            'tableName'=>'products',
            'tableFieldWhere'=>'active',
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
     * Инициирует создание SELECT запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
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
            
            if (!empty(\Yii::$app->filters->categories)) {
                if (!$this->queryForCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->params[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->filters->categories;
            }
            
            if (!empty(\Yii::$app->filters->subcategory)) {
                if (!$this->queryForSubCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->params[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->filters->subcategory;
            }
            
            if (empty(\Yii::$app->filters->getActive) || empty(\Yii::$app->filters->getNotActive)) {
                $filterActive = null;
                if (!empty(\Yii::$app->filters->getActive)) {
                    $filterActive = true;
                } elseif (!empty(\Yii::$app->filters->getNotActive)) {
                    $filterActive = false;
                }
                if (!is_null($filterActive)) {
                    $where = $this->getWhere(
                        $this->config['products']['tableName'],
                        $this->config['products']['tableFieldWhere'],
                        $this->config['products']['tableFieldWhere']
                    );
                    if (!is_string($where)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $where;
                    $this->_mapperObject->params[':' . $this->config['products']['tableFieldWhere']] = $filterActive;
                }
            }
            
            if (!$this->addSelectEnd()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
