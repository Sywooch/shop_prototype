<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AbstractBaseSessionFinder;
use app\collections\{BaseSessionCollection,
    SessionCollectionInterface};

/**
 * Тестирует класс AbstractBaseSessionFinder
 */
class AbstractBaseSessionFinderTests extends TestCase
{
    private $finder;
    
    public function setUp()
    {
        $this->finder = new class() extends AbstractBaseSessionFinder {
            public function find() {}
        };
    }
    
    /**
     * Тестирует свойства AbstractBaseSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AbstractBaseSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод AbstractBaseSessionFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $this->finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод AbstractBaseSessionFinder::setCollection
     */
    public function testSetCollection()
    {
        $collection = new class() extends BaseSessionCollection {};
        $this->finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($this->finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInstanceOf(SessionCollectionInterface::class, $result);
    }
}
