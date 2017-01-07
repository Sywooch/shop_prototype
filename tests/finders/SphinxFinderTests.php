<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SphinxFinder;

/**
 * Тестирует класс SphinxFinder
 */
class SphinxFinderTests extends TestCase
{
    /**
     * Тестирует свойства SphinxFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SphinxFinder::class);
        
        $this->assertTrue($reflection->hasProperty('search'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SphinxFinder::find
     * если пуст SphinxFinder::search
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: search
     */
    public function testFindEmptySearch()
    {
        $finder = new SphinxFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SphinxFinder::find
     */
    public function testFind()
    {
        $finder = new SphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'search');
        $reflection->setValue($finder, 'пиджак');
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
