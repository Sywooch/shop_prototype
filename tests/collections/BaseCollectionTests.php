<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\{BaseCollection,
    CollectionInterface,
    LightPagination,
    PaginationInterface};
use yii\base\Model;
use yii\db\{ActiveRecord,
    Query};

class BaseCollectionTests extends TestCase
{
    public static function setUpBeforeClass()
    {
        \Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS abc_test (id INT, text VARCHAR(255))')->execute();
        \Yii::$app->db->createCommand('INSERT INTO abc_test (id, text) VALUES (1,\'one\'), (2,\'two\')')->execute();
    }
    
    /**
     * Тестирует свойства BaseCollection
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BaseCollection::class);
        
        $this->assertTrue($reflection->hasProperty('query'));
        $this->assertTrue($reflection->hasProperty('pagination'));
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
        $query = new class() extends Query {};
        
        $collection = new BaseCollection();
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
     * если BaseCollection::items пуст
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
     * если BaseCollection::items содержит объекты
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
     * Тестирует метод BaseCollection::getModels
     */
    public function testGetModels()
    {
        $model = new class() extends ActiveRecord {
            public static function tableName()
            {
                return 'abc_test';
            }
        };
        
        $query = $model::className()::find();
        
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
        $this->assertInstanceOf($model::className(), $result[0]);
    }
    
    /**
     * Тестирует метод BaseCollection::getArrays
     */
    public function testGetArrays()
    {
        $model = new class() extends ActiveRecord {
            public static function tableName()
            {
                return 'abc_test';
            }
        };
        
        $query = $model::className()::find();
        
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
        $this->assertInternalType('array', $result[0]);
    }
    
    /**
     * Тестирует метод BaseCollection::getModel
     */
    public function testGetModel()
    {
        $model = new class() extends ActiveRecord {
            public static function tableName()
            {
                return 'abc_test';
            }
        };
        
        $query = $model::className()::find();
        
        $collection = new BaseCollection();
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $query);
        
        $result = $collection->getModel();
        
        $this->assertInstanceOf($model::className(), $result);
        
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
        $model = new class() extends ActiveRecord {
            public static function tableName()
            {
                return 'abc_test';
            }
        };
        
        $query = $model::className()::find();
        
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
        $pagination = new class() extends LightPagination {};
        
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
        $pagination = new class() extends LightPagination {};
        
        $collection = new BaseCollection();
        $reflection = new \ReflectionProperty($collection, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($collection, $pagination);
        
        $result = $collection->getPagination();
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
         \Yii::$app->db->createCommand('DROP TABLE abc_test')->execute();
    }
}
