<?php

namespace app\finders;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;
use app\finders\FinderInterface;

/**
 * Базовый класс для finders
 */
abstract class AbstractBaseFinder extends Model implements FinderInterface
{
    use ExceptionsTrait;
    
    /**
     * Загружает данные в свойства модели
     * @param $data массив данных
     * @return bool
     */
    public function load($data, $formName=null)
    {
        try {
            return parent::load($data, '');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
