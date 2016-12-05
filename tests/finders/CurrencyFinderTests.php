<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencyFinder;
use app\tests\sources\fixtures\CurrencyFixture;
use app\tests\DbManager;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\Model;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyFinder
 */
class CurrencyFinderTests extends TestCase
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
     * Тестирует свойства CurrencyFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyFinder::class);
        
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод CurrencyFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new CurrencyFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод CurrencyFinder::setCollection
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
        $finder = new CurrencyFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод CurrencyFinder::find
     * при отсутствии CurrencyFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindCollectionEmpty()
    {
        $finder = new CurrencyFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CurrencyFinder::find
     */
    public function testFind()
    {
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
        
        $finder = new CurrencyFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(CurrencyModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `currency`.`id`, `currency`.`code` FROM `currency`";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
