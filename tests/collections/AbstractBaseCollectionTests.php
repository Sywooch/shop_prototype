<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\{AbstractBaseCollection,
    CollectionInterface,
    LightPagination,
    PaginationInterface};
use yii\base\Model;
use yii\db\{ActiveRecord,
    Query};

class AbstractBaseCollectionTests extends TestCase
{
    private $collection;
    
    public static function setUpBeforeClass()
    {
        \Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS abc_test (id INT, text VARCHAR(255))')->execute();
        \Yii::$app->db->createCommand('INSERT INTO abc_test (id, text) VALUES (1,\'one\'), (2,\'two\')')->execute();
    }
    
    public function setUp()
    {
        $this->collection = new class() extends AbstractBaseCollection {};
    }
    
    /**
     * Тестирует свойства AbstractBaseCollection
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AbstractBaseCollection::class);
        
        $this->assertTrue($reflection->hasProperty('query'));
        $this->assertTrue($reflection->hasProperty('pagination'));
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::setQuery
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetQueryError()
    {
        $query = new class() {};
        $this->collection->setQuery($query);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::setQuery
     */
    public function testSetQuery()
    {
        $query = new class() extends Query {};
        $this->collection->setQuery($query);
        
        $reflection = new \ReflectionProperty($this->collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Query::class, $result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::getQuery
     */
    public function testGetQuery()
    {
        $query = new class() extends Query {};
        $reflection = new \ReflectionProperty($this->collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, $query);
        
        $this->assertNotEmpty($this->collection->query);
        $this->assertInstanceOf(Query::class, $this->collection->query);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::add
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testAddError()
    {
        $model = new class() {};
        $this->collection->add($model);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::add
     * если AbstractBaseCollection::items пуст
     */
    public function testAddEmptyItems()
    {
        $model = new class() extends Model{
            public $id = 1;
        };

        $this->collection->add($model);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        $this->assertSame($model, $result[0]);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::add
     * если AbstractBaseCollection::items содержит объекты
     */
    public function testAdd()
    {
        $model_1 = new class() extends Model{
            public $id = 1;
        };
        $model_2 = new class() extends Model{
            public $id = 2;
        };
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, [$model_1]);
        
        $result = $reflection->getValue($this->collection);
        
        $this->assertCount(1, $result);
        
        $this->collection->add($model_1);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertCount(1, $result);
        
        $this->collection->add($model_2);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertCount(2, $result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::getModels
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
        
        $reflection = new \ReflectionProperty($this->collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, $query);
        
        $result = $this->collection->getModels();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf($model::className(), $result[0]);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::getArrays
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
        
        $reflection = new \ReflectionProperty($this->collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, $query);
        
        $result = $this->collection->getArrays();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result[0]);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::getModel
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
        
        $reflection = new \ReflectionProperty($this->collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, $query);
        
        $result = $this->collection->getModel();
        
        $this->assertInstanceOf($model::className(), $result);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertCount(1, $result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::getArray
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
        
        $reflection = new \ReflectionProperty($this->collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, $query);
        
        $result = $this->collection->getArray();
        
        $this->assertTrue(is_array($result));
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertCount(1, $result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::setPagination
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPaginationError()
    {
        $pagination = new class() {};
        $this->collection->setPagination($pagination);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::setPagination
     */
    public function testSetPagination()
    {
        $pagination = new class() extends LightPagination {};
        $this->collection->setPagination($pagination);
        
        $reflection = new \ReflectionProperty($this->collection, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::getPagination
     */
    public function testGetPagination()
    {
        $pagination = new class() extends LightPagination {};
        
        $reflection = new \ReflectionProperty($this->collection, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->collection, $pagination);
        
        $result = $this->collection->getPagination();
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(PaginationInterface::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
         \Yii::$app->db->createCommand('DROP TABLE abc_test')->execute();
    }
}
