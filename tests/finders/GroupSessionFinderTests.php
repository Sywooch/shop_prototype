<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\GroupSessionFinder;
use app\collections\{AbstarctBaseSessionCollection,
    SessionCollectionInterface};

class GroupSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства GroupSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GroupSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
    }
    
    /**
     * Тестирует метод GroupSessionFinder::rules
     */
    public function testRules()
    {
        $finder = new GroupSessionFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('key', $finder->errors);
        
        $finder->attributes = ['key'=>'key'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод GroupSessionFinder::find
     * если пуст GroupSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Key»
     */
    public function testFindValidationError()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('some', [['id'=>1, 'one'=>'One-one', 'two-one'=>3134.35], ['id'=>2, 'one-two'=>'One', 'two-two'=>3134.35], ['id'=>3, 'one-three'=>'One', 'two-three'=>3134.35]]);
        $session->close();
        
        $collection = new class() extends AbstarctBaseSessionCollection {};
        $finder = new GroupSessionFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
    }
    
    /**
     * Тестирует метод GroupSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('some', [['id'=>1, 'one'=>'One-one', 'two-one'=>3134.35], ['id'=>2, 'one-two'=>'One', 'two-two'=>3134.35], ['id'=>3, 'one-three'=>'One', 'two-three'=>3134.35]]);
        $session->close();
        
        $collection = new class() extends AbstarctBaseSessionCollection {
            public function addArray(array $array){
                $this->items[] = $array;
            }
        };
        $finder = new GroupSessionFinder();
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
        $this->assertCount(3, $result);
        foreach ($result as $element) {
            $this->assertInternalType('array', $element);
        }
    }
}
