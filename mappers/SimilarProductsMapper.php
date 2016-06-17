<?php

namespace app\mappers;

use app\mappers\AbstractGetGroupFromModelMapper;
use yii\helpers\ArrayHelper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class SimilarProductsMapper extends AbstractGetGroupFromModelMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SimilarProductsQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        if (empty($this->params)) {
            $this->params = [
                ':' . \Yii::$app->params['idKey']=>$this->model->id,
                ':' . \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                ':' . \Yii::$app->params['subCategoryKey']=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
            ];
        }
    }
}
