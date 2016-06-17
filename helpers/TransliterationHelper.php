<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;

/**
 * Предоставляет методы для транслитерации
 */
class TransliterationHelper
{
    use ExceptionsTrait;
    
    private static $_inputArray = array();
    private static $_outputArray = array();
    private static $_matrix = [
        'а'=>'a',
        'б'=>'b',
        'в'=>'v',
        'г'=>'g',
        'д'=>'d',
        'е'=>'e',
        'ё'=>'e',
        'ж'=>'zh',
        'з'=>'z',
        'и'=>'i',
        'й'=>'i',
        'к'=>'k',
        'л'=>'l',
        'м'=>'m',
        'н'=>'n',
        'о'=>'o',
        'п'=>'p',
        'р'=>'r',
        'с'=>'s',
        'т'=>'t',
        'у'=>'u',
        'ф'=>'f',
        'х'=>'h',
        'ц'=>'c',
        'ч'=>'ch',
        'ш'=>'sh',
        'щ'=>'sh',
        'ъ'=>'',
        'ы'=>'y',
        'ь'=>'',
        'э'=>'e',
        'ю'=>'yu',
        'я'=>'ya',
    ];
    
    /**
     * Транслитерирует криллицу в латиницу
     * @param string $var транслитерируемая строка
     * @return string
     */
    public static function getTransliteration($string)
    {
        try {
            self::$_inputArray = preg_split('//u', preg_replace('/ /', '', $string), -1, PREG_SPLIT_NO_EMPTY);
            foreach (self::$_inputArray as $letter) {
                self::$_outputArray[] = self::$_matrix[mb_strtolower($letter, 'UTF-8')];
            }
        } catch (\Exception $e) {
            self::throwStaticException($e, __METHOD__);
        }
        return implode('', self::$_outputArray);
    }
}
