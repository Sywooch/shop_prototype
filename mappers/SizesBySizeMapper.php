<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\SizesModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class SizesBySizeMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\SizesBySizeQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\SizesObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof SizesModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                if (empty($this->model->size)) {
                    throw new ErrorException('Отсутствуют данные для выполнения запроса!');
                }
                $this->params = [':size'=>$this->model->size];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
