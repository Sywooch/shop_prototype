<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\helpers\ArrayHelper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class SimilarProductsMapper extends AbstractGetMapper
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
        
        if (!isset($this->model)) {
            throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
        }
        
        if (empty($this->params)) {
            $this->params = [
                ':' . \Yii::$app->params['idKey']=>$this->model->id,
                ':' . \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                ':' . \Yii::$app->params['subCategoryKey']=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
            ];
        }
    }
}
