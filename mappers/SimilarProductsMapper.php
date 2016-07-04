<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;
use app\models\ProductsModel;

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
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof ProductsModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                if (empty(\Yii::$app->params['categoryKey'])) {
                    throw new ErrorException('Не поределен categoryKey!');
                }
                if (empty(\Yii::$app->params['subCategoryKey'])) {
                    throw new ErrorException('Не поределен subCategoryKey!');
                }
                $this->params = [
                    ':' . \Yii::$app->params['idKey']=>$this->model->id,
                    ':' . \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                    ':' . \Yii::$app->params['subCategoryKey']=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
                ];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
