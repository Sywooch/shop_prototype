<?php

namespace app\tests\helpers;

use PHPUnit\Framework\TestCase;
use app\helpers\TransliterationHelper;

/**
 * Тестирует класс app\helpers\TransliterationHelper
 */
class TransliterationHelperTests extends TestCase
{
    private static $string = 'обычно он таким не был ontario';
    private static $expectString = 'obychnoontakimnebylontario';
    private static $expectStringSeparate = 'obychno-on-takim-ne-byl-ontario';
    
    /**
     * Тестирует метод TransliterationHelper::getTransliteration
     */
    public function testGetTransliteration()
    {
        $result = TransliterationHelper::getTransliteration(self::$string);
        
        $this->assertEquals(self::$expectString, $result);
    }
    
    /**
     * Тестирует метод TransliterationHelper::getTransliterationSeparate
     */
    public function testGetTransliterationSeparate()
    {
        $result = TransliterationHelper::getTransliterationSeparate(self::$string);
        
        $this->assertEquals(self::$expectStringSeparate, $result);
    }
}
