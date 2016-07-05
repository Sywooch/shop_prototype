<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;

/**
 * Предоставляет методы для транслитерации
 */
class PasswordHelper
{
    use ExceptionsTrait;
    
    private static $_length = 10;
    private static $_outputArray = array();
    private static $_matrix = [
        ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'],
        [1, 2, 3, 4, 5, 6, 7, 8, 9],
    ];
    
    /**
     * Конструирует случайный пароль
     * @return string
     */
    public static function getPassword()
    {
        try {
            for ($x = 0 ; $x < self::$_length; $x++) {
                $element = self::$_matrix[mt_rand(0, count(self::$_matrix) - 1)];
                self::$_outputArray[] = $element[mt_rand(0, count($element) - 1)];
            }
            return implode('', self::$_outputArray);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
