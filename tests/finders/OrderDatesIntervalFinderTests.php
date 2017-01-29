<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OrderDatesIntervalFinder;

/**
 * Тестирует класс OrderDatesIntervalFinder
 */
class OrderDatesIntervalFinderTests extends TestCase
{
    /**
     * Тестирует свойства OrderDatesIntervalFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrderDatesIntervalFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод OrderDatesIntervalFinder::find
     */
    public function testFind()
    {
        $finder = new OrderDatesIntervalFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
