<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizesProductFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{SizesFixture,
    ProductsSizesFixture,
    ProductsFixture};
use app\models\SizesModel;

/**
 * Тестирует класс SizesProductFinder
 */
class SizesProductFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class,
                'products'=>ProductsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SizesProductFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesProductFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_product'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SizesProductFinder::setId_product
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetId_productError()
    {
        $id_product = null;
        
        $finder = new SizesProductFinder();
        $finder->setId_product($id_product);
    }
    
    /**
     * Тестирует метод SizesProductFinder::setId_product
     */
    public function testSetId_product()
    {
        $id_product = 1;
        
        $finder = new SizesProductFinder();
        $finder->setId_product($id_product);
        
        $reflection = new \ReflectionProperty($finder, 'id_product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод SizesProductFinder::find
     */
    public function testFind()
    {
        $finder = new SizesProductFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_product');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SizesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
