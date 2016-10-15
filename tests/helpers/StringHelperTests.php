<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\StringHelper;

/**
 * Тестирует класс app\helpers\StringHelper
 */
class StringHelperTests extends TestCase
{
    private static $_rawString = '/first/second-3';
    private static $_rawString2 = '/first/second-third-2';
    private static $_rawStringSearch = '/search?search=пиджак';
    private static $_rawStringSearch2 = '/search-2?search=пиджак';
    
    /**
     * Тестирует метод StringHelper::cutPage
     */
    public function testCutPage()
    {
        $result = StringHelper::cutPage(self::$_rawString);
        $expectedString = '/first/second';
        $this->assertEquals($expectedString, $result);
        
        $result = StringHelper::cutPage(self::$_rawString2);
        $expectedString = '/first/second-third';
        $this->assertEquals($expectedString, $result);
        
        $result = StringHelper::cutPage(self::$_rawStringSearch);
        $expectedString = '/search?search=пиджак';
        $this->assertEquals($expectedString, $result);
        
        $result = StringHelper::cutPage(self::$_rawStringSearch2);
        $expectedString = '/search?search=пиджак';
        $this->assertEquals($expectedString, $result);
    }
}
