<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OneSessionFinder;
use app\collections\{BaseSessionCollection,
    SessionCollectionInterface};

class OneSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства OneSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OneSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
    }
    
    /**
     * Тестирует метод OneSessionFinder::rules
     */
    public function testRules()
    {
        $finder = new OneSessionFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('key', $finder->errors);
        
        $finder->attributes = ['key'=>'key'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод OneSessionFinder::find
     * если пуст OneSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Key»
     */
    public function testFindValidationError()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('some', ['id'=>1, 'one'=>'One', 'two'=>3134.35]);
        $session->close();
        
        $collection = new class() extends BaseSessionCollection {};
        $finder = new OneSessionFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
    }
    
     /**
     * Тестирует метод OneSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('some', ['id'=>1, 'one'=>'One', 'two'=>3134.35]);
        $session->close();
        
        $collection = new class() extends BaseSessionCollection {
            public function addArray(array $array){
                $this->items[] = $array;
            }
        };
        $finder = new OneSessionFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'some');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(SessionCollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertInternalType('array', $result[0]);
    }
}
