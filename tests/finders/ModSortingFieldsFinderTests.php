<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ModSortingFieldsFinder;

/**
 * Тестирует класс ModSortingFieldsFinder
 */
class ModSortingFieldsFinderTests extends TestCase
{
    /**
     * Тестирует свойства ModSortingFieldsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModSortingFieldsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ModSortingFieldsFinder::find
     */
    public function testFind()
    {
        $finder = new ModSortingFieldsFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
