<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\CategoriesModel;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
class SubcategoryForCategoryMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SubcategoryForCategoryQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\SubcategoryObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof CategoriesModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                if (empty(\Yii::$app->params['idKey'])) {
                    throw new ErrorException('Не поределен idKey!');
                }
                $this->params = [':' . \Yii::$app->params['idKey']=>$this->model->id];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
