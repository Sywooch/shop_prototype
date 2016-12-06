<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\BaseTrait;

/**
 * Тестирует трейт BaseTrait
 */
class BaseTraitTests extends TestCase
{
    private $class;
    
    public function setUp()
    {
        $this->class = new class() {
            use BaseTrait;
            public $items = [];
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
        $result = $reflection->setValue($this->class, [$array_1]);
        
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
        $result = $reflection->setValue($this->class, [$array_1]);
        
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
        $result = $reflection->setValue($this->class, [$array_1]);
        
        $result = $this->class->isEmpty();
        $this->assertFalse($result);
    }
}
