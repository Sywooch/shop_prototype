<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQuery;
use app\models\ProductsModel;

/**
 * Конструирует объект запроса, возвращающий массив объектов ProductsModel
 */
class GetProductsQuery extends AbstractBaseQuery
{
    public function __construct($config=array())
    {
        try {
            $this->className = ProductsModel::className();
            parent::__construct($config);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует объект запроса yii\db\ActiveQuery для выборки массива строк
     * @return object ActiveQuery
     */
    public function getAll()
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $this->query->innerJoin('categories', '[[categories.id]]=[[products.id_category]]');
                $this->query->andWhere(['categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $this->query->innerJoin('subcategory', '[[subcategory.id]]=[[products.id_subcategory]]');
                $this->query->andWhere(['subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
            }
            
            $this->query->andWhere(['products.active'=>true]);
            
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!$this->extraWhere()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!$this->addLimit()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!$this->addOrder()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            $this->query->with('categories', 'subcategory');
            
            return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует объект запроса yii\db\ActiveQuery для выборки одной строки
     * @return object ActiveQuery
     */
    public function getOne()
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!$this->extraWhere()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
           return $this->query; 
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
