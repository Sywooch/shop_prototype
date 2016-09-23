<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use app\queries\AbstractBaseQuery;
use app\models\SubcategoryModel;

/**
 * Конструирует объект запроса, возвращающий массив объектов SubcategoryModel
 */
class GetSubcategoryQuery extends AbstractBaseQuery
{
    public function __construct($config=[])
    {
        try {
            $this->className = SubcategoryModel::className();
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
            
            return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует объект запроса yii\db\ActiveQuery для выборки одной строки
     * @return object ActiveQuery
     */
    public function getOne(): ActiveQuery
    {
        try {
            if (!$this->getSelect()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
            if (!$this->extraWhere()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
           return $this->query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
