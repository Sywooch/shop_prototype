<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SortingTypesFinder;
use app\collections\{BaseCollection,
    CollectionInterface};

/**
 * Тестирует класс SortingTypesFinder
 */
class SortingTypesFinderTests extends TestCase
{
    /**
     * Тестирует метод SortingTypesFinder::find
     * если пуст SortingTypesFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFinderEmptyCollection()
    {
        $finder = new SortingTypesFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SortingTypesFinder::find
     */
    public function testFinder()
    {
        $collection = new class() extends BaseCollection {
            public function addArray(array $array)
            {
                $this->items[] = $array;
            }
        };
        
        $finder = new SortingTypesFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($result, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($result);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        
        foreach ($result as $item) {
            $this->assertInternalType('array', $item);
            $this->assertArrayHasKey('name', $item);
            $this->assertArrayHasKey('value', $item);
        }
    }
}
