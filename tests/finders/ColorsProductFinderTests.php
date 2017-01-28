<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsProductFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture};
use app\models\ColorsModel;

/**
 * Тестирует класс ColorsProductFinder
 */
class ColorsProductFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
                'products'=>ProductsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ColorsProductFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsProductFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_product'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ColorsProductFinder::setId_product
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetId_productError()
    {
        $id_product = null;
        
        $finder = new ColorsProductFinder();
        $finder->setId_product($id_product);
    }
    
    /**
     * Тестирует метод ColorsProductFinder::setId_product
     */
    public function testSetId_product()
    {
        $id_product = 1;
        
        $finder = new ColorsProductFinder();
        $finder->setId_product($id_product);
        
        $reflection = new \ReflectionProperty($finder, 'id_product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод ColorsProductFinder::find
     */
    public function testFind()
    {
        $finder = new ColorsProductFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_product');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ColorsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
