<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\StringHelper;

/**
 * Тестирует класс app\helpers\StringHelper
 */
class StringHelperTests extends TestCase
{
    /**
     * Тестирует метод StringHelper::cutPage
     */
    public function testCutPage()
    {
        $result = StringHelper::cutPage('/first/second-3');
        $expectedString = '/first/second';
        $this->assertEquals($expectedString, $result);
        
        $result = StringHelper::cutPage('/first/second-third-2');
        $expectedString = '/first/second-third';
        $this->assertEquals($expectedString, $result);
        
        $result = StringHelper::cutPage('/search?search=пиджак');
        $expectedString = '/search?search=пиджак';
        $this->assertEquals($expectedString, $result);
        
        $result = StringHelper::cutPage('/search-2?search=пиджак');
        $expectedString = '/search?search=пиджак';
        $this->assertEquals($expectedString, $result);
    }
}
