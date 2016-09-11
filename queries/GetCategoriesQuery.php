<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQuery;
use app\models\CategoriesModel;

/**
 * Конструирует объект запроса, возвращающий массив объектов CategoriesModel
 */
class GetCategoriesQuery extends AbstractBaseQuery
{
    public function __construct($config=array())
    {
        try {
            $this->className = CategoriesModel::className();
            parent::__construct($config);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует объект запроса yii\db\ActiveQuery
     * @return object ActiveQuery
     */
    public function getAll()
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException(\Yii::t('base/errors', 'Error in constructing a query object!'));
            }
            
            if (!$this->addOrder()) {
                throw new ErrorException(\Yii::t('base/errors', 'Error in constructing a query object!'));
            }
            
            $this->query->with('subcategory', 'products');
            
            return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует объект запроса yii\db\ActiveQuery для выборки одной строки
     * @return object ActiveQuery
     */
    public function getOne()
    {
        
    }
}
