<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CommentsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\models\{CommentsModel,
    ProductsModel};

/**
 * Тестирует класс CommentsFinder
 */
class CommentsFinderTests extends TestCase
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
     * Тестирует свойства CommentsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CommentsFinder::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $finder = new CommentsFinder();
        $finder->setProduct($product);
    }
    
    /**
     * Тестирует метод CommentsFinder::setProduct
     */
    public function testSetProduct()
    {
        $product = new class() extends ProductsModel {};
        
        $finder = new CommentsFinder();
        $finder->setProduct($product);
        
        $reflection = new \ReflectionProperty($finder, 'product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод CommentsFinder::find
     * если пуст CommentsFinder::product
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: product
     */
    public function testFindEmptyProduct()
    {
        $finder = new CommentsFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CommentsFinder::find
     */
    public function testFind()
    {
        $product = new class() {
            public $id = 1;
        };
        
        $finder = new CommentsFinder();
        
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
