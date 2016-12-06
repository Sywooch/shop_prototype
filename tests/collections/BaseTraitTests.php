<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\BaseTrait;
use yii\base\Model;
use app\exceptions\ExceptionsTrait;

/**
 * Тестирует трейт BaseTrait
 */
class BaseTraitTests extends TestCase
{
    private $class;
    
    public function setUp()
    {
        $this->class = new class() {
            use BaseTrait, ExceptionsTrait;
            public $items = [];
            public function add(Model $object) {
                $this->items[] = $object;
            }
        };
    }
    
    /**
     * Тестирует метод BaseTrait::addArray
     * если static::items пуст
     */
    public function testAddArrayEmpty()
    {
        $array_1 = ['id'=>1, 'text'=>'one'];
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $result = $reflection->getValue($this->class);
        
        $this->assertEmpty($result);
        
        $this->class->addArray($array_1);
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $result = $reflection->getValue($this->class);
        
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertInternalType('array', $result[0]);
    }
    
    /**
     * Тестирует метод BaseTrait::addArray
     * добавляю те же элементы
     */
    public function testAddArraySame()
    {
        $array_1 = ['id'=>1, 'text'=>'one'];
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setValue($this->class, [$array_1]);
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $result = $reflection->getValue($this->class);
        
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        
        $this->class->addArray($array_1);
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $result = $reflection->getValue($this->class);
        
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }
    
    /**
     * Тестирует метод BaseTrait::addArray
     */
    public function testAddArray()
    {
        $array_1 = ['id'=>1, 'text'=>'one'];
        $array_2 = ['id'=>2, 'text'=>'one'];
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setValue($this->class, [$array_1]);
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $result = $reflection->getValue($this->class);
        
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        
        $this->class->addArray($array_2);
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $result = $reflection->getValue($this->class);
        
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }
    
    /**
     * Тестирует метод BaseTrait::isEmpty
     * если static::items пуст
     */
    public function testIsEmptyTrue()
    {
        $result = $this->class->isEmpty();
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод BaseTrait::isEmpty
     * если static::items содержит элементы
     */
    public function testIsEmptyFalse()
    {
        $array_1 = ['id'=>1, 'text'=>'one'];
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setValue($this->class, [$array_1]);
        
        $result = $this->class->isEmpty();
        $this->assertFalse($result);
    }
    
    /**
     * Тестирует метод BaseTrait::isArrays
     * если static::items содержит объекты
     */
    public function testIsArraysFalse()
    {
        $array_1 = ['id'=>1];
        
        $object_1 = new class() {
           public $id = 2; 
        };
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setValue($this->class, [$array_1, $object_1]);
        
        $result = $this->class->isArrays();
        
        $this->assertFalse($result);
    }
    
    /**
     * Тестирует метод BaseTrait::isArrays
     * если static::items содержит только массивы
     */
    public function testIsArraysTrue()
    {
        $array_1 = ['id'=>1];
        $array_2 = ['id'=>2];
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setValue($this->class, [$array_1, $array_2]);
        
        $result = $this->class->isArrays();
        
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод BaseTrait::isObjects
     * если static::items содержит массивы
     */
    public function testIsObjectsFalse()
    {
        $array_1 = ['id'=>1];
        
        $object_1 = new class() {
           public $id = 2; 
        };
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setValue($this->class, [$array_1, $object_1]);
        
        $result = $this->class->isObjects();
        
        $this->assertFalse($result);
    }
    
    /**
     * Тестирует метод BaseTrait::isObjects
     * если static::items содержит только массивы
     */
    public function testIsObjectsTrue()
    {
        $object_1 = new class() {
           public $id = 1; 
        };
        $object_2 = new class() {
           public $id = 2; 
        };
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setValue($this->class, [$object_1, $object_2]);
        
        $result = $this->class->isObjects();
        
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод BaseTrait::map
     */
    public function testMap()
    {
        $model_1 = new class() {
            public $one = 'one';
            public $two = 'two';
        };
        
        $model_2 = new class() {
            public $one = 'three';
            public $two = 'four';
        };
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->class, [$model_1, $model_2]);
        $result = $this->class->map('one', 'two');
        
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('one', $result);
        $this->assertArrayHasKey('three', $result);
        $this->assertContains('two', $result);
        $this->assertContains('four', $result);
    }
    
    /**
     * Тестирует метод BaseTrait::sort
     */
    public function testSort()
    {
        $model_1 = new class() {
            public $id = 1;
        };
        $model_2 = new class() {
            public $id = 2;
        };
        $model_3 = new class() {
            public $id = 3;
        };
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->class, [$model_3, $model_1, $model_2]);
        $result = $reflection->getValue($this->class);
        
        $this->assertSame(3, $result[0]->id);
        $this->assertSame(1, $result[1]->id);
        $this->assertSame(2, $result[2]->id);
        
        $this->class->sort('id');
        
        $result = $reflection->getValue($this->class);
        
        $this->assertSame(1, $result[0]->id);
        $this->assertSame(2, $result[1]->id);
        $this->assertSame(3, $result[2]->id);
        
        $this->class->sort('id', SORT_DESC);
        
        $result = $reflection->getValue($this->class);
        
        $this->assertSame(3, $result[0]->id);
        $this->assertSame(2, $result[1]->id);
        $this->assertSame(1, $result[2]->id);
    }
    
    /**
     * Тестирует метод BaseTrait::hasEntity
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testHasEntityError()
    {
        $model = new class() {};
        $this->class->hasEntity($model);
    }
    
    /**
     * Тестирует метод BaseTrait::hasEntity
     * если BaseTrait::items содержит массивы
     */
    public function testHasEntityArrays()
    {
        $model_1 = new class() extends Model {
            public $id = 1;
        };
        $model_2 = new class() extends Model {
            public $id = 2;
        };
        $model_3 = new class() extends Model {
            public $id = 3;
        };
        
        $array_1 = ['id'=>1];
        $array_2 = ['id'=>2];
        $array_3 = ['id'=>3];
        
        $result = $this->class->hasEntity($model_1);
        
        $this->assertFalse($result);
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->class, [$array_1, $array_2, $array_3]);
        
        $result = $this->class->hasEntity($model_2);
        $this->assertTrue($result);
        
        $result = $this->class->hasEntity($model_1);
        $this->assertTrue($result);
        
        $result = $this->class->hasEntity($model_3);
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод BaseTrait::hasEntity
     * если BaseTrait::items содержит объекты
     */
    public function testHasEntityObjects()
    {
        $model_1 = new class() extends Model {
            public $id = 1;
        };
        $model_2 = new class() extends Model {
            public $id = 2;
        };
        $model_3 = new class() extends Model {
            public $id = 3;
        };
        
        $result = $this->class->hasEntity($model_1);
        
        $this->assertFalse($result);
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->class, [$model_1, $model_2, $model_3]);
        
        $result = $this->class->hasEntity($model_2);
        $this->assertTrue($result);
        
        $result = $this->class->hasEntity($model_1);
        $this->assertTrue($result);
        
        $result = $this->class->hasEntity($model_3);
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод BaseTrait::update
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testUpdateError()
    {
        $model = new class() {};
        $this->class->update($model);
    }
    
    /**
     * Тестирует метод BaseTrait::update
     * если BaseTrait::items содержит массивы
     */
    public function testUpdateArrays()
    {
        $array_1 = ['id'=>1, 'name'=>'one'];
        $array_2 = ['id'=>2];
        
        $model_1_2 = new class() extends Model {
            public $id = 1;
            public $name = 'one two';
        };
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->class, [$array_1, $array_2]);
        
        $result = $reflection->getValue($this->class);
        
        foreach ($result as $array) {
            if ($array['id'] === 1) {
                $this->assertSame('one', $array['name']);
            }
        }
        
        $this->class->update($model_1_2);
        
        $result = $reflection->getValue($this->class);
        
        $this->assertCount(2, $result);
        
        foreach ($result as $array) {
            if ($array['id'] === 1) {
                $this->assertSame('one two', $array['name']);
            }
        }
    }
    
    /**
     * Тестирует метод BaseTrait::update
     * если BaseTrait::items содержит объекты
     */
    public function testUpdateObjects()
    {
        $model_1 = new class() extends Model {
            public $id = 1;
            public $name = 'one';
        };
        $model_2 = new class() extends Model {
            public $id = 2;
        };
        
        $model_1_2 = new class() extends Model {
            public $id = 1;
            public $name = 'one two';
        };
        
        $reflection = new \ReflectionProperty($this->class, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->class, [$model_1, $model_2]);
        
        $result = $reflection->getValue($this->class);
        
        foreach ($result as $object) {
            if ($object->id === 1) {
                $this->assertSame('one', $object->name);
            }
        }
        
        $this->class->update($model_1_2);
        
        $result = $reflection->getValue($this->class);
        
        $this->assertCount(2, $result);
        
        foreach ($result as $object) {
            if ($object->id === 1) {
                $this->assertSame('one two', $object->name);
            }
        }
    }
}
