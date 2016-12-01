<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\{BaseCollection,
    PaginationInterface};
use yii\base\Model;
use yii\db\Query;

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
        $this->assertNotEmpty($result);
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
    
    /**
     * Тестирует метод BaseCollection::getArray
     */
    public function testGetArray()
    {
        $model_1 = new class() extends Model {
            public $one = 'one';
            public $two = 'two';
        };
        
        $model_2 = new class() extends Model {
            public $three = 'three';
            public $four = 'four';
        };
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2]);
        $result = $collection->getArray();
        
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        $this->assertTrue(is_array($result[0]));
    }
    
    /**
     * Тестирует метод BaseCollection::setPagination
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPaginationError()
    {
        $pagination = new class() {};
        $collection = new BaseCollection();
        $collection->setPagination($pagination);
    }
    
    /**
     * Тестирует метод BaseCollection::setPagination
     */
    public function testSetPagination()
    {
        $pagination = new class() implements PaginationInterface {
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){}
            public function getOffset(){}
            public function getLimit(){}
            public function getPage(){}
        };
        $collection = new BaseCollection();
        $collection->setPagination($pagination);
        
        $reflection = new \ReflectionProperty($collection, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
}
