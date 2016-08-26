<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\mappers\AbstractGetMapper;
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
            
            if (empty($this->params)) {
                $this->params = [':main'=>1];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
