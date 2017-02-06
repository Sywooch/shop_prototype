<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductIdFinder
 */
class ProductIdFinderTests extends TestCase
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
     * Тестирует свойства ProductIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductIdFinder::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $id = 'id';
        
        $widget = new ProductIdFinder();
        $widget->setId($id);
    }
    
    /**
     * Тестирует метод ProductIdFinder::setId
     */
    public function testSetId()
    {
        $id = 35;
        
        $widget = new ProductIdFinder();
        $widget->setId($id);
        
        $reflection = new \ReflectionProperty($widget, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод ProductIdFinder::find
     * если пуст ProductIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new ProductIdFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductIdFinder::find
     */
    public function testFind()
    {
        $finder = new ProductIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 2);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
