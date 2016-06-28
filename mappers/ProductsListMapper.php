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
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->limit)) {
                if (empty(\Yii::$app->params['limit'])) {
                    throw new ErrorException('Не поределен limit!');
                }
                $this->limit = \Yii::$app->params['limit'];
            }
            
            if (empty(\Yii::$app->params['orderTypePointer'])) {
                throw new ErrorException('Не поределен orderTypePointer!');
            }
            if (empty(\Yii::$app->params['orderByType'])) {
                throw new ErrorException('Не поределен orderByType!');
            }
            if (!is_null(\Yii::$app->request->get(\Yii::$app->params['orderTypePointer']))) {
                $this->orderByType = \Yii::$app->request->get(\Yii::$app->params['orderTypePointer']);
            } elseif (empty($this->orderByType)) {
                $this->orderByType = \Yii::$app->params['orderByType'];
            }
            
            if (empty(\Yii::$app->params['orderFieldPointer'])) {
                throw new ErrorException('Не поределен orderFieldPointer!');
            }
            if (!is_null(\Yii::$app->request->get(\Yii::$app->params['orderFieldPointer']))) {
                $this->orderByField = \Yii::$app->request->get(\Yii::$app->params['orderFieldPointer']);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
