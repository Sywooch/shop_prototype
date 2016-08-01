<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;

/**
 * Получает строки с данными о товарах из БД, конструирует из каждой строки объект данных
 */
class ProductsListMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductsListQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    /**
     * @var array массив значений, полученный из sphynx
     */
    public $sphynxArray = array();
    
    public function init()
    {
        try {
            if (empty(\Yii::$app->params['limit'])) {
                throw new ErrorException('Не поределен limit!');
            }
            if (empty(\Yii::$app->params['defaultOrderByType'])) {
                throw new ErrorException('Не поределен defaultOrderByType!');
            }
            
            parent::init();
            
            if (empty($this->limit)) {
                $this->limit = \Yii::$app->params['limit'];
            }
            
            if (!empty(\Yii::$app->filters->sortingType)) {
                $this->orderByType = \Yii::$app->filters->sortingType;
            } elseif (empty($this->orderByType)) {
                $this->orderByType = \Yii::$app->params['defaultOrderByType'];
            }
            
            if (!empty(\Yii::$app->filters->sortingField)) {
                $this->orderByField = \Yii::$app->filters->sortingField;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
