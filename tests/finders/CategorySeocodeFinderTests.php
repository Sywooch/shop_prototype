<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategorySeocodeFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\CategoriesModel;
use yii\db\Query;

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
        
        $this->assertTrue($reflection->hasProperty('seocode'));
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::rules
     */
    public function testRules()
    {
        $finder = new CategorySeocodeFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('seocode', $finder->errors);
        
        $finder->attributes = ['seocode'=>'seocode'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        $fixture = self::$dbClass->categories['category_1'];
        
        $finder = new CategorySeocodeFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'seocode');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $fixture['seocode']);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(CategoriesModel::class, $result->modelClass);
        
        $expectedQuery = sprintf("SELECT `categories`.`name`, `categories`.`seocode` FROM `categories` WHERE `categories`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
