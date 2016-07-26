<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;
use app\models\SubcategoryModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class SubcategoryBySeocodeMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SubcategoryBySeocodeQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\SubcategoryObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof SubcategoryModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                $this->params = [':seocode'=>$this->model->seocode];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
