<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MainCurrencyFinder;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\{Model,
    Object};
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

class MainCurrencyFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства MainCurrencyFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MainCurrencyFinder::class);
        
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод MainCurrencyFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new MainCurrencyFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод MainCurrencyFinder::setCollection
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
        $finder = new MainCurrencyFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод MainCurrencyFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends Object implements CollectionInterface {
            private $query;
            public function setQuery(Query $query){
                $this->query = $query;
            }
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
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
        
        $finder = new MainCurrencyFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
