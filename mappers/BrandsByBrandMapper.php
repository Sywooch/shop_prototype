<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\BrandsModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class BrandsByBrandMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\BrandsByBrandQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\BrandsObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof BrandsModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                if (empty($this->model->brand)) {
                    throw new ErrorException('Отсутствуют данные для выполнения запроса!');
                }
                $this->params = [':brand'=>$this->model->brand];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
