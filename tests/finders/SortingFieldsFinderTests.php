<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SortingFieldsFinder;

/**
 * Тестирует класс SortingFieldsFinder
 */
class SortingFieldsFinderTests extends TestCase
{
    /**
     * Тестирует свойства SortingFieldsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SortingFieldsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SortingFieldsFinder::find
     */
    public function testFind()
    {
        $finder = new SortingFieldsFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
