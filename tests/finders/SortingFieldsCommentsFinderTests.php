<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SortingFieldsCommentsFinder;

/**
 * Тестирует класс SortingFieldsCommentsFinder
 */
class SortingFieldsCommentsFinderTests extends TestCase
{
    /**
     * Тестирует свойства SortingFieldsCommentsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SortingFieldsCommentsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SortingFieldsCommentsFinder::find
     */
    public function testFind()
    {
        $finder = new SortingFieldsCommentsFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
