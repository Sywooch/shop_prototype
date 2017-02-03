<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ActiveStatusesFinder;

/**
 * Тестирует класс ActiveStatusesFinder
 */
class ActiveStatusesFinderTests extends TestCase
{
    /**
     * Тестирует свойства ActiveStatusesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ActiveStatusesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ActiveStatusesFinder::find
     */
    public function testFind()
    {
        $finder = new ActiveStatusesFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
