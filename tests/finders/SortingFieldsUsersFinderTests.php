<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SortingFieldsUsersFinder;

/**
 * Тестирует класс SortingFieldsUsersFinder
 */
class SortingFieldsUsersFinderTests extends TestCase
{
    /**
     * Тестирует свойства SortingFieldsUsersFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SortingFieldsUsersFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SortingFieldsUsersFinder::find
     */
    public function testFind()
    {
        $finder = new SortingFieldsUsersFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
