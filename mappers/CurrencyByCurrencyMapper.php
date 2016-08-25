<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
use app\models\CurrencyModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class CurrencyByCurrencyMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CurrencyByCurrencyQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CurrencyObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof CurrencyModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                if (empty($this->model->currency)) {
                    throw new ErrorException('Отсутствуют данные для выполнения запроса!');
                }
                $this->params = [':currency'=>$this->model->currency];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
