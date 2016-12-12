<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SubcategoryFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\models\SubcategoryModel;
use yii\db\Query;

/**
 * Тестирует класс SubcategoryFinder
 */
class SubcategoryFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SubcategoryFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategoryFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
    }
    
    /**
     * Тестирует метод SubcategoryFinder::rules
     */
    public function testRules()
    {
        $finder = new SubcategoryFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('id', $finder->errors);
        
        $finder->attributes = ['id'=>'id'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод SubcategoryFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        $fixture = self::$dbClass->subcategory['subcategory_1'];
        
        $finder = new SubcategoryFinder();
        
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
        $this->assertSame(SubcategoryModel::class, $result->modelClass);
        
        $expectedQuery = sprintf("SELECT `subcategory`.`name`, `subcategory`.`seocode` FROM `subcategory` WHERE `subcategory`.`id`='%d'", $fixture['id']);
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
