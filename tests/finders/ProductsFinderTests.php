<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsFinder;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\{Model,
    Object};
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

class ProductsFinderTests extends TestCase
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
     * Тестирует свойства ProductsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод ProductsFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new ProductsFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод ProductsFinder::setCollection
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
        $finder = new ProductsFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsFinder::rules
     */
    public function testRules()
    {
        $finder = new ProductsFinder();
        $finder->attributes = [
            'category'=>'category',
            'subcategory'=>'subcategory',
            'page'=>'page'
        ];
        
        $this->assertSame('category', $finder->category);
        $this->assertSame('subcategory', $finder->subcategory);
        $this->assertSame('page', $finder->page);
    }
    
    /**
     * Тестирует метод ProductsFinder::load
     */
    public function testLoad()
    {
        $data = [
            'category'=>'category',
            'subcategory'=>'subcategory',
            'page'=>'page'
        ];
        
        $finder = new ProductsFinder();
        $finder->load($data);
        
        $this->assertSame('category', $finder->category);
        $this->assertSame('subcategory', $finder->subcategory);
        $this->assertSame('page', $finder->page);
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     */
    public function testFind()
    {
        $pagination = new class() extends Object implements PaginationInterface {
            public function setPageSize(int $size){}
            public function setPage(int $number){}
            public function setTotalCount(Query $query){}
            public function getPageCount(){}
            public function getOffset(){
                return 0;
            }
            public function getLimit(){
                return 3;
            }
            public function getPage(){}
        };
        
        $collection = new class() extends Object implements CollectionInterface {
            private $query;
            private $pagination;
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
            public function getPagination(){
                return $this->pagination;
            }
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        $finder = new ProductsFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'pagination');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $pagination);
        
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
