<?php

namespace app\queries;

use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!$this->addSelectHead()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!$this->addCategoriesSubcategory()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!$this->addOrder()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (!$this->addLimit()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     * @return true
     */
    protected function addSelectHead()
    {
        try {
            $this->_mapperObject->query->addSelect(['categories'=>'categories.seocode', 'subcategory'=>'subcategory.seocode']);
            
            $this->_mapperObject->query->distinct();
            
            $this->_mapperObject->query->innerJoin('categories', '[[products.id_categories]]=[[categories.id]]');
            
            $this->_mapperObject->query->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
            
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
            if (empty(\Yii::$app->params['filterKeys'])) {
                throw new ErrorException('Не поределен filterKeys!');
            }
            
            $getArrayKeys = array_keys(array_filter(\Yii::$app->filters->attributes));
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $this->_mapperObject->query->innerJoin('products_' . $filter, 'products.id=products_' . $filter . '.id_products');
                    $this->_mapperObject->query->innerJoin($filter, 'products_' . $filter . '.id_' . $filter . '=' . $filter . '.id');
                    $this->_mapperObject->query->andWhere([$filter . '.id'=>\Yii::$app->filters->$filter]);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, фильрующую по категории, подкатегории
     * @return boolean
     */
    protected function addCategoriesSubcategory()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            $this->_mapperObject->query->andFilterWhere([
                'categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                'subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
            ]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, задающую порядок сортировки
     * @return boolean
     */
    protected function addOrder()
    {
        try {
            if (empty($this->_mapperObject->orderByField)) {
                throw new ErrorException('Не задано имя столбца для сортировки!');
            }
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            if (empty($this->_mapperObject->orderByType)) {
                throw new ErrorException('Не задан тип сортировки!');
            }
            
            $this->_mapperObject->query->orderBy([$this->_mapperObject->tableName . '.' . $this->_mapperObject->orderByField=>$this->_mapperObject->orderByType]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, ограничивающую выборку
     * @return boolean
     */
    protected function addLimit()
    {
        try {
            if (empty(\Yii::$app->params['pagePointer'])) {
                throw new ErrorException('Не поределен pagePointer!');
            }
            if (empty($this->_mapperObject->limit)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            $this->_mapperObject->query->limit($this->_mapperObject->limit);
            
            if (in_array(\Yii::$app->params['pagePointer'], array_keys(\Yii::$app->request->get()))) {
                $this->_mapperObject->query->offset(\Yii::$app->request->get(\Yii::$app->params['pagePointer'] * $this->_mapperObject->limit - $this->_mapperObject->limit));
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
