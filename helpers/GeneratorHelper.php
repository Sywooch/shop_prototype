<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Предоставляет функциональность для генерации данных
 */
class GeneratorHelper
{
    use ExceptionsTrait;
    
    /**
     * Метод-генератор, возвращает объект, запрошенный в текущей итерации
     * @param array $inputArray массив данных
     * @return yield
     */
    public static function generate(Array $inputArray)
    {
        try {
            if (empty($inputArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            
            foreach ($inputArray as $object) {
                yield $object;
            }
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
