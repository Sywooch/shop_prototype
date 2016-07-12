<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;
use app\models\CurrencyModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class CurrencyByMainMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CurrencyByMainQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\CurrencyObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            /*if (empty($this->model) || !$this->model instanceof CurrencyModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }*/
            
            if (!\Yii::$app->params['valueDefaultCurrency']) {
                throw new ErrorException('Отсутствует значение valueDefaultCurrency!');
            }
            
            if (empty($this->params)) {
                $this->params = [':main'=>\Yii::$app->params['valueDefaultCurrency']];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
