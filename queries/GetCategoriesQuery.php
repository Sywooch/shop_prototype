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
     * Конструирует объект запроса yii\db\ActiveQuery
     * @return object ActiveQuery
     */
    public function getAll()
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            if (!$this->addOrder()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            $this->query->with('subcategory', 'products');
            
            return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
