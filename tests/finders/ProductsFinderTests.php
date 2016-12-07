<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsFinder;
use app\collections\{BaseCollection,
    CollectionInterface,
    LightPagination};
use yii\db\Query;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

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
     * Тестирует метод ProductsFinder::find
     */
    public function testFind()
    {
        $pagination = new class() extends LightPagination {
            public function getOffset(){
                return 0;
            }
            public function getLimit(){
                return 3;
            }
        };
        
        $collection = new class() extends BaseCollection {};
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
        $this->assertSame(ProductsModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `products`.`id`, `products`.`name`, `products`.`price`, `products`.`short_description`, `products`.`images`, `products`.`seocode` FROM `products` WHERE `products`.`active`=TRUE ORDER BY `products`.`date` DESC LIMIT 3";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
