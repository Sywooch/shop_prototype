<?php

namespace app\savers;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;
use app\savers\SaverInterface;

/**
 * Абстрактный класс для savers
 */
abstract class AbstractBaseSaver extends Model implements SaverInterface
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
