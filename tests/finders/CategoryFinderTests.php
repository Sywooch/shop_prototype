<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategoryFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\CategoriesModel;
use yii\db\Query;

/**
 * Тестирует класс CategoryFinder
 */
class CategoryFinderTests extends TestCase
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
     * Тестирует свойства CategoryFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoryFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
    }
    
    /**
     * Тестирует метод CategoryFinder::rules
     */
    public function testRules()
    {
        $finder = new CategoryFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('id', $finder->errors);
        
        $finder->attributes = ['id'=>'id'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод CategoryFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        $fixture = self::$dbClass->categories['category_1'];
        
        $finder = new CategoryFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $fixture['id']);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(CategoriesModel::class, $result->modelClass);
        
        $expectedQuery = sprintf("SELECT `categories`.`name`, `categories`.`seocode` FROM `categories` WHERE `categories`.`id`='%d'", $fixture['id']);
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
