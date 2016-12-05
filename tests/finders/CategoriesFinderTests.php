<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategoriesFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\{Model,
    Object};
use app\models\CategoriesModel;

/**
 * Тестирует класс CategoriesFinder
 */
class CategoriesFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CategoriesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод CategoriesFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new CategoriesFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод CategoriesFinder::setCollection
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
        $finder = new CategoriesFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод CategoriesFinder::find
     * при отсутствии CategoriesFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindCollectionEmpty()
    {
        $finder = new CategoriesFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CategoriesFinder::find
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
        
        $finder = new CategoriesFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(CategoriesModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode`, `categories`.`active` FROM `categories`";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
