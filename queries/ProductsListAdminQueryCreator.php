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
     * @var array массив для выборки данных с учетом категории или(и) подкатегории, а также фильтров
     */
    public $categoriesArrayFilters = [
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
            if ($reflectionParent->hasProperty('categoriesArrayFilters')) {
                $parentCategoriesArrayFilters = $reflectionParent->getProperty('categoriesArrayFilters')->getValue(new ProductsListQueryCreator);
            }
            $this->categoriesArrayFilters = array_merge($parentCategoriesArrayFilters, $this->categoriesArrayFilters);
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
            
            if (\Yii::$app->filters->categories) {
                if (!$this->queryForCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->params[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->filters->categories;
            }
            
            if (\Yii::$app->filters->subcategory) {
                if (!$this->queryForSubCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->params[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->filters->subcategory;
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters['products']['tableName'],
                $this->categoriesArrayFilters['products']['tableFieldWhere'],
                $this->categoriesArrayFilters['products']['tableFieldWhere']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            $this->_mapperObject->params[':' . $this->categoriesArrayFilters['products']['tableFieldWhere']] = \Yii::$app->filters->active;
            
            if (!$this->addSelectEnd()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
