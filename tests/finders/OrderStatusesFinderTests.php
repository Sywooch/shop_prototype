<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OrderStatusesFinder;

/**
 * Тестирует класс OrderStatusesFinder
 */
class OrderStatusesFinderTests extends TestCase
{
    /**
     * Тестирует свойства OrderStatusesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrderStatusesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод OrderStatusesFinder::find
     */
    public function testFind()
    {
        $finder = new OrderStatusesFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
