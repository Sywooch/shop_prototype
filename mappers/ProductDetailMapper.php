<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class ProductDetailMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductDetailQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->params)) {
                if (empty(\Yii::$app->params['idKey'])) {
                        throw new ErrorException('Не поределен idKey!');
                    }
                $this->params = [':' . \Yii::$app->params['idKey']=>\Yii::$app->request->get(\Yii::$app->params['idKey'])];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
