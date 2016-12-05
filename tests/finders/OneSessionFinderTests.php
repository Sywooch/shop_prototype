<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OneSessionFinder;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\Model;

class OneSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства OneSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OneSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод OneSessionFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new OneSessionFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод OneSessionFinder::setCollection
     */
    public function testSetCollection()
    {
        $collection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function isArrays(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        $finder = new OneSessionFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
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
     * Тестирует метод OneSessionFinder::load
     */
    public function testLoad()
    {
        $data = ['key'=>'key'];
        
        $finder = new OneSessionFinder();
        $finder->load($data);
        
        $this->assertSame('key', $finder->key);
    }
    
    /**
     * Тестирует метод OneSessionFinder::find
     * ошибка валидации при отсутствии занчения для OneSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Key»
     */
    public function testFindValidationError()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('some', ['id'=>1, 'one'=>'One', 'two'=>3134.35]);
        $session->close();
        
        $collection = new class() implements CollectionInterface {
            private $items;
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){
                $this->items[] = $array;
            }
            public function isEmpty(){
                return true;
            }
            public function isArrays(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
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
        
        $collection = new class() implements CollectionInterface {
            private $items;
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){
                $this->items[] = $array;
            }
            public function isEmpty(){
                return true;
            }
            public function isArrays(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        $finder = new OneSessionFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'some');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertInternalType('array', $result[0]);
    }
}
