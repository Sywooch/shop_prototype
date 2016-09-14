<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\TransliterationHelper;

/**
 * Тестирует класс app\helpers\TransliterationHelper
 */
class TransliterationHelperTests extends TestCase
{
    private static $_string = 'обычно он таким не был ontario';
    private static $_expectString = 'obychnoontakimnebylontario';
    private static $_expectStringSeparate = 'obychno-on-takim-ne-byl-ontario';
    
    /**
     * Тестирует метод TransliterationHelper::getTransliteration
     */
    public function testGetTransliteration()
    {
        $result = TransliterationHelper::getTransliteration(self::$_string);
        
        $this->assertEquals(self::$_expectString, $result);
    }
    
    /**
     * Тестирует метод TransliterationHelper::getTransliterationSeparate
     */
    public function testGetTransliterationSeparate()
    {
        $result = TransliterationHelper::getTransliterationSeparate(self::$_string);
        
        $this->assertEquals(self::$_expectStringSeparate, $result);
    }
}
