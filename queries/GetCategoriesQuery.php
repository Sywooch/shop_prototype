<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use app\queries\AbstractBaseQuery;
use app\models\CategoriesModel;

/**
 * Конструирует объект запроса, возвращающий массив объектов CategoriesModel
 */
class GetCategoriesQuery extends AbstractBaseQuery
{
    public function __construct($config=[])
    {
        try {
            $this->className = CategoriesModel::className();
            parent::__construct($config);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует объект запроса yii\db\ActiveQuery для выборки массива строк
     * @return object ActiveQuery
     */
    public function getAll(): ActiveQuery
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
            if (!$this->addOrder()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
            $this->query->with('subcategory', 'products');
            
            return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function getOne()
    {
    }
}
