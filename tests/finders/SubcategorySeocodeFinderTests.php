<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SubcategorySeocodeFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\models\SubcategoryModel;
use yii\db\Query;

/**
 * Тестирует класс SubcategorySeocodeFinder
 */
class SubcategorySeocodeFinderTests extends TestCase
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
     * Тестирует свойства SubcategorySeocodeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategorySeocodeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('seocode'));
    }
    
    /**
     * Тестирует метод SubcategorySeocodeFinder::rules
     */
    public function testRules()
    {
        $finder = new SubcategorySeocodeFinder();
        $finder->attributes = [];
        $finder->validate();
        
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('seocode', $finder->errors);
        
        $finder->attributes = ['seocode'=>'seocode'];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод SubcategorySeocodeFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        $fixture = self::$dbClass->subcategory['subcategory_1'];
        
        $finder = new SubcategorySeocodeFinder();
        
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
        $this->assertSame(SubcategoryModel::class, $result->modelClass);
        
        $expectedQuery = sprintf("SELECT `subcategory`.`name`, `subcategory`.`seocode` FROM `subcategory` WHERE `subcategory`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
