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
    }
}
