<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;

/**
 * Добавляет записи в БД
 */
class CurrencyUpdateMainNullMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\CurrencyUpdateMainNullQueryCreator';
    
    public function init()
    {
        try {
            parent::init();
            $this->params = [':main'=>false];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
