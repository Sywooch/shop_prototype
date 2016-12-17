<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SortingTypesFinder;

/**
 * Тестирует класс SortingTypesFinder
 */
class SortingTypesFinderTests extends TestCase
{
    /**
     * Тестирует свойства SortingTypesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SortingTypesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SortingTypesFinder::find
     */
    public function testFind()
    {
        $finder = new SortingTypesFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
