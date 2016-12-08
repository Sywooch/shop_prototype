<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SphinxFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\sphinx\Query;

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
    }
    
    /**
     * Тестирует метод SphinxFinder::rules
     */
    public function testRules()
    {
        $finder = new SphinxFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertArrayHasKey('search', $finder->errors);
        
        $finder = new SphinxFinder();
        $finder->attributes = ['search'=>'some text'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод SphinxFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new SphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'search');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'adidas');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        
        $expectedQuery = "SELECT `id` FROM `shop` WHERE MATCH('[[@* \\\"adidas\\\"]]')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
}
