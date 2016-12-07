<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AbstractBaseFinder;
use app\collections\{BaseCollection,
    CollectionInterface};

/**
 * Тестирует класс AbstractBaseFinder
 */
class AbstractBaseFinderTests extends TestCase
{
    private $finder;
    
    public function setUp()
    {
        $this->finder = new class() extends AbstractBaseFinder {
            public function find() {}
        };
    }
    
    /**
     * Тестирует свойства AbstractBaseFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AbstractBaseFinder::class);
        
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод AbstractBaseFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $this->finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод AbstractBaseFinder::setCollection
     */
    public function testSetCollection()
    {
        $collection = new class() extends BaseCollection {};
        $this->finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($this->finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
}
