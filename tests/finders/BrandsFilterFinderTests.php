<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BrandsFilterFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;
use app\models\BrandsModel;

class BrandsFilterFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства BrandsFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandsFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::rules
     */
    public function testRules()
    {
        $finder = new BrandsFilterFinder();
        $finder->attributes = [
            'category'=>'category',
            'subcategory'=>'subcategory',
        ];
        
        $this->assertSame('category', $finder->category);
        $this->assertSame('subcategory', $finder->subcategory);
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new BrandsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(BrandsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products` ON `products`.`id_brand`=`brands`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::find
     * если BrandsFilterFinder::category не пустое
     */
    public function testFindCategory()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new BrandsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'shoes');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(BrandsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products` ON `products`.`id_brand`=`brands`.`id` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` WHERE (`products`.`active`=TRUE) AND (`categories`.`seocode`='shoes')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::find
     * если BrandsFilterFinder::subcategory не пустое
     */
    public function testFindSubcategory()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new BrandsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'shoes');
        
        $reflection = new \ReflectionProperty($finder, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'sneakers');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(BrandsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products` ON `products`.`id_brand`=`brands`.`id` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`products`.`active`=TRUE) AND (`categories`.`seocode`='shoes')) AND (`subcategory`.`seocode`='sneakers')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
