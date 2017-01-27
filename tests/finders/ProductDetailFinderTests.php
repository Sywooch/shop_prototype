<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductDetailFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductDetailFinder
 */
class ProductDetailFinderTests extends TestCase
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
     * Тестирует свойства ProductDetailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductDetailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductDetailFinder::setSeocode
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSeocodeError()
    {
        $seocode = null;
        
        $widget = new ProductDetailFinder();
        $widget->setSeocode($seocode);
    }
    
    /**
     * Тестирует метод ProductDetailFinder::setSeocode
     */
    public function testSetSeocode()
    {
        $seocode = 'seocode';
        
        $widget = new ProductDetailFinder();
        $widget->setSeocode($seocode);
        
        $reflection = new \ReflectionProperty($widget, 'seocode');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ProductDetailFinder::find
     * если пуст ProductDetailFinder::seocode
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: seocode
     */
    public function testFindEmptySeocode()
    {
        $finder = new ProductDetailFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductDetailFinder::find
     */
    public function testFind()
    {
        $fixture = self::$dbClass->products['product_1'];
        
        $finder = new ProductDetailFinder();
        
        $reflection = new \ReflectionProperty($finder, 'seocode');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $fixture['seocode']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
