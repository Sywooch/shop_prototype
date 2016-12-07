<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsFilterFinder;
use app\collections\{AbstractBaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;
use app\models\ColorsModel;

class ColorsFilterFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ColorsFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::rules
     */
    public function testRules()
    {
        $finder = new ColorsFilterFinder();
        $finder->attributes = [
            'category'=>'category',
            'subcategory'=>'subcategory',
        ];
        
        $this->assertSame('category', $finder->category);
        $this->assertSame('subcategory', $finder->subcategory);
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends AbstractBaseCollection {};
        
        $finder = new ColorsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products` ON `products_colors`.`id_product`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::find
     * если ColorsFilterFinder::category не пустое
     */
    public function testFindCategory()
    {
        $collection = new class() extends AbstractBaseCollection {};
        
        $finder = new ColorsFilterFinder();
        
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
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` WHERE (`products`.`active`=TRUE) AND (`categories`.`seocode`='shoes')";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
