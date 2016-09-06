<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQueryCreator;
use app\models\ProductsModel;

/**
 * Конструирует объект запроса, возвращающий массив объектов ProductsModel
 */
class GetProductsListQuery extends AbstractBaseQueryCreator
{
    public function __construct($config=array())
    {
        $this->className = ProductsModel::className();
        parent::__construct($config);
    }
    
    /**
     * Формирует объект запроса yii\db\ActiveQuery
     * @return object ActiveQuery
     */
    public function getQuery()
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoriesKey']))) {
                $this->_query->innerJoin('categories', '[[categories.id]]=[[products.id_categories]]');
                $this->_query->andWhere(['categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoriesKey'])]);
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $this->_query->innerJoin('subcategory', '[[subcategory.id]]=[[products.id_subcategory]]');
                $this->_query->andWhere(['subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
            }
            
            $this->_query->andWhere(['products.active'=>true]);
            
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!$this->addOrder()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!$this->addLimit()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            $this->_query->with('categories', 'subcategory');
            
            return $this->_query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
