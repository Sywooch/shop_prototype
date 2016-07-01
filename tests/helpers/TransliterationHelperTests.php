<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\TransliterationHelper;

/**
 * Тестирует app\helpers\TransliterationHelper
 */
class TransliterationHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_rawString = 'Валерий Эдуардович';
    private static $_rawLatinString = 'Peter Bankman';
    private static $_expected = 'valeriieduardovich';
    private static $_expectedLatin = 'peterbankman';
    
    /**
     * Тестирует метод TransliterationHelper::getTransliteration
     */
    public function testGetTransliteration()
    {
        $result = TransliterationHelper::getTransliteration(self::$_rawString);
        $this->assertEquals(self::$_expected, $result);
        
        TransliterationHelper::clean();
        
        $result = TransliterationHelper::getTransliteration(self::$_rawLatinString);
        $this->assertEquals(self::$_expectedLatin, $result);
    }
}
