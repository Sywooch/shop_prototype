<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CommentsProductFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\models\{CommentsModel,
    ProductsModel};

/**
 * Тестирует класс CommentsProductFinder
 */
class CommentsProductFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CommentsProductFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsProductFinder::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CommentsProductFinder::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $finder = new CommentsProductFinder();
        $finder->setProduct($product);
    }
    
    /**
     * Тестирует метод CommentsProductFinder::setProduct
     */
    public function testSetProduct()
    {
        $product = new class() extends ProductsModel {};
        
        $finder = new CommentsProductFinder();
        $finder->setProduct($product);
        
        $reflection = new \ReflectionProperty($finder, 'product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод CommentsProductFinder::find
     * если пуст CommentsProductFinder::product
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: product
     */
    public function testFindEmptyProduct()
    {
        $finder = new CommentsProductFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CommentsProductFinder::find
     */
    public function testFind()
    {
        $product = new class() {
            public $id = 1;
        };
        
        $finder = new CommentsProductFinder();
        
        $reflection = new \ReflectionProperty($finder, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $product);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CommentsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
