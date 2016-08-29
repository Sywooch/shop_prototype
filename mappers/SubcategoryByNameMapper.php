<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\SubcategoryModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class SubcategoryByNameMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SubcategoryByNameQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\SubcategoryObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof SubcategoryModel || empty($this->model->name)) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
