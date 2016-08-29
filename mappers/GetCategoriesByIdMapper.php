<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\CategoriesModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class GetCategoriesByIdMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\GetCategoriesByIdQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CategoriesObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof CategoriesModel || empty($this->model->id)) {
                throw new ErrorException('Не определен объект модели!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
