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
     * Тестирует метод SphinxFinder::setSearch
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSearchError()
    {
        $search = null;
        
        $widget = new SphinxFinder();
        $widget->setSearch($search);
    }
    
    /**
     * Тестирует метод SphinxFinder::setSearch
     */
    public function testSetSearch()
    {
        $search = 'search';
        
        $widget = new SphinxFinder();
        $widget->setSearch($search);
        
        $reflection = new \ReflectionProperty($widget, 'search');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
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
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'пиджак');
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
