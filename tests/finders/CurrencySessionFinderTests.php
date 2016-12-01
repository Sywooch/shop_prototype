<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencySessionFinder;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\Model;

class CurrencySessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства CurrencySessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencySessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new CurrencySessionFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::setCollection
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
        $finder = new CurrencySessionFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::rules
     */
    public function testRules()
    {
        $finder = new CurrencySessionFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('key', $finder->errors);
        
        $finder->attributes = ['key'=>'key'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::load
     */
    public function testLoad()
    {
        $data = ['key'=>'key'];
        
        $finder = new CurrencySessionFinder();
        $finder->load($data);
        
        $this->assertSame('key', $finder->key);
    }
    
     /**
     * Тестирует метод CurrencySessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('currency', ['id'=>1, 'code'=>'USD', 'change'=>1.354]);
        $session->close();
        
        $collection = new class() implements CollectionInterface {
            private $items;
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){
                $this->items[] = $array;
            }
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
        $finder = new CurrencySessionFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'currency');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        /*$reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);*/
    }
}
