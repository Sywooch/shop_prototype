<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategorySeocodeFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\CategoriesModel;

/**
 * Тестирует класс CategorySeocodeFinder
 */
class CategorySeocodeFinderTests extends TestCase
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
     * Тестирует свойства CategorySeocodeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategorySeocodeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('collection'));
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::setCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCollectionError()
    {
        $collection = new class() {};
        $finder = new CategorySeocodeFinder();
        $finder->setCollection($collection);
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::setCollection
     */
    public function testSetCollection()
    {
        $collection = new class() extends BaseCollection {};
        $finder = new CategorySeocodeFinder();
        $finder->setCollection($collection);
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::rules
     */
    public function testRules()
    {
        $finder = new CategorySeocodeFinder();
        $finder->attributes = [
            'category'=>'category',
        ];
        
        $this->assertSame('category', $finder->category);
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::load
     */
    public function testLoad()
    {
        $data = [
            'category'=>'category',
        ];
        
        $finder = new CategorySeocodeFinder();
        $finder->load($data);
        
        $this->assertSame('category', $finder->category);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
