<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQueryCreator;
use app\models\CurrencyModel;

/**
 * Конструирует объект запроса, возвращающий массив объектов CurrencyModel
 */
class GetCurrencyListQuery extends AbstractBaseQueryCreator
{
    public function __construct($config=array())
    {
        $this->className = CurrencyModel::className();
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
            
            if (!$this->addOrder()) {
                throw new ErrorException('Ошибка при конструировании объекта запроса!');
            }
            
            return $this->_query;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}