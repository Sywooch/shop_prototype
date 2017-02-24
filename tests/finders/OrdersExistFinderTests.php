<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OrdersExistFinder;

/**
 * Тестирует класс OrdersExistFinder
 */
class OrdersExistFinderTests extends TestCase
{
    /**
     * Тестирует свойства OrdersExistFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrdersExistFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод OrdersExistFinder::find
     */
    public function testFind()
    {
        $finder = new OrdersExistFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
