<?php

namespace app\traits;

use yii\base\ErrorException;

/**
 * Коллекция методов для классов, наследующих AbstractBaseQueryCreator
 */
trait QueriesCreatorTrait
{
    /**
     * Добавляет имя таблицы к имени поля
     * @return string
     */
    protected function addTableName()
    {
        try {
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Не заданы поля!');
            }
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            
            $this->_mapperObject->fields = array_map(function($value) {
                return $this->_mapperObject->tableName . '.' . $value;
            }, $this->_mapperObject->fields);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД
     * @return boolean
     */
    private function addCategoriesSubcategory()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $this->_mapperObject->query->innerJoin('categories', '[[products.id_categories]]=[[categories.id]]');
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subCategoryKey']))) {
                $this->_mapperObject->query->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
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
     * Формирует строку запроса к БД
     * @return boolean
     */
    private function addCategoriesSubcategoryAdmin()
    {
        try {
            if (!empty(\Yii::$app->filters->categories)) {
                $this->_mapperObject->query->innerJoin('categories', '[[products.id_categories]]=[[categories.id]]');
            } 
            if (!empty(\Yii::$app->filters->subcategory)) {
                $this->_mapperObject->query->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
            }
            
            $this->_mapperObject->query->andFilterWhere([
                'categories.seocode'=>\Yii::$app->filters->categories,
                'subcategory.seocode'=>\Yii::$app->filters->subcategory,
            ]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
