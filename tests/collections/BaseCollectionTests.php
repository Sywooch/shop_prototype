<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\{BaseCollection,
    CollectionInterface,
    PaginationInterface};
use yii\base\Model;
use yii\db\Query;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

class BaseCollectionTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства BaseCollection
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BaseCollection::class);
        
        $this->assertTrue($reflection->hasProperty('query'));
        $this->assertTrue($reflection->hasProperty('pagination'));
        $this->assertTrue($reflection->hasProperty('items'));
    }
    
    /**
     * Тестирует метод BaseCollection::setQuery
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetQueryError()
    {
        $query = new class() {};
        $collection = new BaseCollection();
        $collection->setQuery($query);
    }
    
    /**
     * Тестирует метод BaseCollection::setQuery
     */
    public function testSetQuery()
    {
        $query = new class() extends Query {};
        $collection = new BaseCollection();
        $collection->setQuery($query);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Query::class, $result);
    }
    
    /**
     * Тестирует метод BaseCollection::getQuery
     */
    public function testGetQuery()
    {
        $collection = new BaseCollection();
        
        $query = new class() extends Query {};
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $query);
        
        $this->assertNotEmpty($collection->query);
        $this->assertInstanceOf(Query::class, $collection->query);
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
     * при условии, что BaseCollection::items пуст
     */
    public function testAddEmptyItems()
    {
        $model = new class() extends Model{
            public $id = 1;
        };
        $collection = new BaseCollection();
        $collection->add($model);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        $this->assertSame($model, $result[0]);
    }
    
    /**
     * Тестирует метод BaseCollection::add
     * при условии, что BaseCollection::items содержит объекты
     */
    public function testAdd()
    {
        $model_1 = new class() extends Model{
            public $id = 1;
        };
        $model_2 = new class() extends Model{
            public $id = 2;
        };
        
        $collection = new BaseCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1]);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
        
        $collection->add($model_1);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
        
        $collection->add($model_2);
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(2, $result);
    }
    
    /**
     * Тестирует метод BaseCollection::addArray
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testAddArrayError()
    {
        $model = new class() {};
        $collection = new BaseCollection();
        $collection->addArray($model);
    }
    
    /**
     * Тестирует метод BaseCollection::addArray
     * при условии, что BaseCollection::items пуст
     */
    public function testAddArrayEmptyItems()
    {
        $array = ['id'=>1];
        $collection = new BaseCollection();
        $collection->addArray($array);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        $this->assertSame($array, $result[0]);
    }
    
    /**
     * Тестирует метод BaseCollection::addArray
     * при условии, что BaseCollection::items содержит объекты
     */
    public function testAddArray()
    {
        $array_1 = ['id'=>1];
        $array_2 = ['id'=>2];
        
        $collection = new BaseCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1]);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
        
        $collection->addArray($array_1);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
        
        $collection->addArray($array_2);
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(2, $result);
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
     * Тестирует метод BaseCollection::getModels
     */
    public function testGetModels()
    {
        $query = ProductsModel::find();
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $query);
        
        $result = $collection->getModels();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(ProductsModel::class, $result[0]);
    }
    
    /**
     * Тестирует метод BaseCollection::getArrays
     */
    public function testGetArrays()
    {
        $query = ProductsModel::find();
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $query);
        
        $result = $collection->getArrays();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
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
    
    /**
     * Тестирует метод BaseCollection::getPagination
     */
    public function testGetPagination()
    {
        $collection = new BaseCollection();
        
        $pagination = new class() implements PaginationInterface {
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){}
            public function getOffset(){}
            public function getLimit(){}
            public function getPage(){}
        };
        
        $reflection = new \ReflectionProperty($collection, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($collection, $pagination);
        
        $result = $collection->getPagination();
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
    
    /**
     * Тестирует метод BaseCollection::map
     */
    public function testMap()
    {
        $model_1 = new class() extends Model {
            public $one = 'one';
            public $two = 'two';
        };
        
        $model_2 = new class() extends Model {
            public $one = 'three';
            public $two = 'four';
        };
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2]);
        $result = $collection->map('one', 'two');
        
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('one', $result);
        $this->assertArrayHasKey('three', $result);
        $this->assertContains('two', $result);
        $this->assertContains('four', $result);
    }
    
    /**
     * Тестирует метод BaseCollection::sort
     */
    public function testSort()
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
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_3, $model_1, $model_2]);
        $result = $reflection->getValue($collection);
        
        $this->assertSame(3, $result[0]->id);
        $this->assertSame(1, $result[1]->id);
        $this->assertSame(2, $result[2]->id);
        
        $collection->sort('id');
        
        $result = $reflection->getValue($collection);
        
        $this->assertSame(1, $result[0]->id);
        $this->assertSame(2, $result[1]->id);
        $this->assertSame(3, $result[2]->id);
        
        $collection->sort('id', SORT_DESC);
        
        $result = $reflection->getValue($collection);
        
        $this->assertSame(3, $result[0]->id);
        $this->assertSame(2, $result[1]->id);
        $this->assertSame(1, $result[2]->id);
    }
    
    /**
     * Тестирует метод BaseCollection::hasEntity
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testHasEntityError()
    {
        $model = new class() {};
        $collection = new BaseCollection();
        $collection->hasEntity($model);
    }
    
    /**
     * Тестирует метод BaseCollection::hasEntity
     * при условии, что BaseCollection::items содержит объекты
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
        
        $collection = new BaseCollection();
        $result = $collection->hasEntity($model_1);
        
        $this->assertFalse($result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2, $model_3]);
        
        $result = $collection->hasEntity($model_2);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_1);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_3);
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод BaseCollection::hasEntity
     * при условии, что BaseCollection::items содержит массивы
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
        
        $collection = new BaseCollection();
        $result = $collection->hasEntity($model_1);
        
        $this->assertFalse($result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2, $array_3]);
        
        $result = $collection->hasEntity($model_2);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_1);
        $this->assertTrue($result);
        
        $result = $collection->hasEntity($model_3);
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод BaseCollection::update
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testUpdateError()
    {
        $model = new class() {};
        $collection = new BaseCollection();
        $collection->hasEntity($model);
    }
    
    /**
     * Тестирует метод BaseCollection::update
     * при условии, что BaseCollection::items содержит объекты
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
        
        $collection = new BaseCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2]);
        
        $result = $reflection->getValue($collection);
        
        foreach ($result as $object) {
            if ($object->id === 1) {
                $this->assertSame('one', $object->name);
            }
        }
        
        $collection->update($model_1_2);
        
        $result = $reflection->getValue($collection);
        
        $this->assertCount(2, $result);
        
        foreach ($result as $object) {
            if ($object->id === 1) {
                $this->assertSame('one two', $object->name);
            }
        }
    }
    
    /**
     * Тестирует метод BaseCollection::update
     * при условии, что BaseCollection::items содержит массивы
     */
    public function testUpdateArrays()
    {
        $array_1 = ['id'=>1, 'name'=>'one'];
        $array_2 = ['id'=>2];
        
        $model_1_2 = new class() extends Model {
            public $id = 1;
            public $name = 'one two';
        };
        
        $collection = new BaseCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array_1, $array_2]);
        
        $result = $reflection->getValue($collection);
        
        foreach ($result as $array) {
            if ($array['id'] === 1) {
                $this->assertSame('one', $array['name']);
            }
        }
        
        $collection->update($model_1_2);
        
        $result = $reflection->getValue($collection);
        
        $this->assertCount(2, $result);
        
        foreach ($result as $array) {
            if ($array['id'] === 1) {
                $this->assertSame('one two', $array['name']);
            }
        }
    }
    
    /**
     * Тестирует метод BaseCollection::getModel
     */
    public function testGetModel()
    {
        $query = ProductsModel::find();
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $query);
        
        $result = $collection->getModel();
        
        $this->assertInstanceOf(ProductsModel::class, $result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
    }
    
    /**
     * Тестирует метод BaseCollection::getArray
     */
    public function testGetArray()
    {
        $query = ProductsModel::find();
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $query);
        
        $result = $collection->getArray();
        
        $this->assertTrue(is_array($result));
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
