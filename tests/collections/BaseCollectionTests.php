<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\BaseCollection;
use yii\base\Model;

class BaseCollectionTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BaseCollection::class);
        
        $this->assertTrue($reflection->hasProperty('pagination'));
        $this->assertTrue($reflection->hasProperty('items'));
    }
    
    /**
     * Тестирует метод BaseCollection::add
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testAddError()
    {
        $model = new class() {};
        $collection = new BaseCollection();
        $collection->add($model);
    }
    
    /**
     * Тестирует метод BaseCollection::add
     */
    public function testAdd()
    {
        $model = new class() extends Model{};
        $collection = new BaseCollection();
        $collection->add($model);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals($model, $result[0]);
    }
    
    /**
     * Тестирует метод BaseCollection::isEmpty
     */
    public function testIsEmpty()
    {
        $collection = new BaseCollection();
        
        $this->assertTrue($collection->isEmpty());
        
        $model = new class() extends Model{};
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $model);
        
        $this->assertFalse($collection->isEmpty());
    }
}
