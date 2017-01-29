<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\DateHelper;

/**
 * Тестирует класс DateHelper
 */
class DateHelperTests extends TestCase
{
    /**
     * Тестирует метод DateHelper::getToday00
     */
    public function testGetToday00()
    {
        $result = DateHelper::getToday00();
        
        $this->assertInternalType('integer', $result);
        $this->assertEquals(10, strlen($result));
        
        sleep(5);
        
        $result2 = DateHelper::getToday00();
        
        $this->assertEquals($result, $result2);
    }
    
    /**
     * Тестирует метод DateHelper::getDaysAgo00
     */
    public function testGetDaysAgo00()
    {
        $result = DateHelper::getDaysAgo00(3);
        
        $this->assertInternalType('integer', $result);
        $this->assertEquals(10, strlen($result));
    }
}
