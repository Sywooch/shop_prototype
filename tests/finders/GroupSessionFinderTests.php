<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\GroupSessionFinder;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\Model;

class GroupSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства GroupSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GroupSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод GroupSessionFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new GroupSessionFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод GroupSessionFinder::setCollection
     */
    public function testSetCollection()
    {
        $collection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        $finder = new GroupSessionFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
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
     * Тестирует метод GroupSessionFinder::load
     */
    public function testLoad()
    {
        $data = ['key'=>'key'];
        
        $finder = new GroupSessionFinder();
        $finder->load($data);
        
        $this->assertSame('key', $finder->key);
    }
    
    /**
     * Тестирует метод GroupSessionFinder::find
     * ошибка валидации при отсутствии занчения для GroupSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Key»
     */
    public function testFindValidationError()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('some', [['id'=>1, 'one'=>'One-one', 'two-one'=>3134.35], ['id'=>2, 'one-two'=>'One', 'two-two'=>3134.35], ['id'=>3, 'one-three'=>'One', 'two-three'=>3134.35]]);
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
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
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
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        $finder = new GroupSessionFinder();
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
        $this->assertCount(3, $result);
        foreach ($result as $element) {
            $this->assertInternalType('array', $element);
        }
    }
}
