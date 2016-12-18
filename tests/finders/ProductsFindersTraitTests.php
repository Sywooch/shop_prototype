<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsFindersTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SubcategoryFixture};
use app\collections\{LightPagination,
    ProductsCollection};
use yii\db\Query;
use app\models\ProductsModel;
use app\filters\ProductsFilters;

/**
 * Тестирует класс ProductsFindersTrait
 */
class ProductsFindersTraitTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new class {
            use ProductsFindersTrait;
            public $storage;
            public $filters;
            public $page;
        };
    }
    
    /**
     * Тестирует метод ProductsFindersTrait::createCollection
     */
    public function testCreateCollection()
    {
        $this->finder->createCollection();
        
        $reflection = new \ReflectionProperty($this->finder, 'storage');
        $result = $reflection->getValue($this->finder);
        
        $this->assertInstanceOf(ProductsCollection::class, $result);
        
        $reflection = new \ReflectionProperty($result, 'pagination');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($result);
        
        $this->assertInstanceOf(LightPagination::class, $result);
    }
    
    /**
     * Тестирует метод ProductsFindersTrait::createQuery
     */
    public function testCreateQuery()
    {
        $result = $this->finder->createQuery();
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ProductsModel::class, $result->modelClass);
        
        $expected = "SELECT `products`.`id`, `products`.`name`, `products`.`price`, `products`.`short_description`, `products`.`images`, `products`.`seocode` FROM `products` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expected, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод ProductsFindersTrait::addFilters
     */
    public function testAddFilters()
    {
        $filters = new class() extends ProductsFilters {
            public $sizes = [1, 2];
            public $colors = [1, 2];
            public $brands = [1, 2];
        };
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->finder, $filters);
        
        $result = $this->finder->addFilters(ProductsModel::find());
        
        $this->assertInstanceOf(Query::class, $result);
        
        $expected = "SELECT `products`.* FROM `products` INNER JOIN `products_colors` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products_sizes` ON `products_sizes`.`id_product`=`products`.`id` INNER JOIN `sizes` ON `sizes`.`id`=`products_sizes`.`id_size` WHERE ((`colors`.`id` IN (1, 2)) AND (`sizes`.`id` IN (1, 2))) AND (`products`.`id_brand` IN (1, 2))";
        
        $this->assertSame($expected, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод ProductsFindersTrait::addPagination
     */
    public function testAddPagination()
    {
        $collection = new ProductsCollection(['pagination'=>new LightPagination()]);
        
        $reflection = new \ReflectionProperty($this->finder, 'storage');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->finder, $collection);
        
        $reflection = new \ReflectionProperty($this->finder, 'page');
        $result = $reflection->setValue($this->finder, 2);
        
        $result = $this->finder->addPagination(ProductsModel::find());
        
        $this->assertInstanceOf(Query::class, $result);
        
        $expected = "SELECT * FROM `products` LIMIT 3 OFFSET 3";
        
        $this->assertSame($expected, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод ProductsFindersTrait::addSorting
     */
    public function testAddSorting()
    {
        $filters = new class() extends ProductsFilters {
            public $sortingField = 'price';
            public $sortingType = SORT_DESC;
        };
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->finder, $filters);
        
        $result = $this->finder->addSorting(ProductsModel::find());
        
        $this->assertInstanceOf(Query::class, $result);
        
        $expected = "SELECT * FROM `products` ORDER BY `products`.`price` DESC";
        
        $this->assertSame($expected, $result->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод ProductsFindersTrait::get
     */
    public function testGet()
    {
        $collection = new ProductsCollection(['pagination'=>new LightPagination()]);
        
        $reflection = new \ReflectionProperty($this->finder, 'storage');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->finder, $collection);
        
        $this->finder->get(ProductsModel::find());
        
        $reflection = new \ReflectionProperty($this->finder, 'storage');
        $reflection->setAccessible(true);
        $collection = $reflection->getValue($this->finder);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
