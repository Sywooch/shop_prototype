<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;

/**
 * Коллекция методов для транслитерации
 */
class TransliterationHelper
{
    /**
     * @var string разделитель слов после транслитерации
     */
    public static $separator = '-';
    /**
     * @var array массив сопоставлений кириллических и латинских символов 
     * для транслитерации
     */
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
        '.'=>'',
        ','=>'',
        ';'=>'',
        '!'=>'',
        '?'=>'',
        ':'=>''
    ];
    
    /**
     * Транслитерирует группу кириллических символов в латиницу
     * @param string $string транслитерируемая строка
     * @return string
     */
    public static function getTransliteration(string $string)
    {
        try {
            $inputArray = preg_split('//u', preg_replace('/ /', '', $string), -1, PREG_SPLIT_NO_EMPTY);
            $outputArray = [];
            foreach ($inputArray as $letter) {
                $letter = mb_strtolower($letter, 'UTF-8');
                if (in_array($letter, array_keys(self::$_matrix))) {
                    $outputArray[] = self::$_matrix[$letter];
                    continue;
                }
                $outputArray[] = $letter;
            }
            return implode('', $outputArray);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Транслитерирует группу кириллических символов в латиницу, 
     * объединяя результирующие слова разделителем
     * @param string $string транслитерируемая строка
     * @return string
     */
    public static function getTransliterationSeparate(string $string)
    {
        try {
            $inputArray = explode(' ', $string);
            $outputArray = [];
            foreach ($inputArray as $word) {
                $outputArray[] = self::getTransliteration($word);
            }
            return implode(self::$separator, $outputArray);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
