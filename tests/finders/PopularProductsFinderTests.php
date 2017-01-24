<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PopularProductsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс PopularProductsFinder
 */
class PopularProductsFinderTests extends TestCase
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
     * Тестирует свойства PopularProductsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PopularProductsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PopularProductsFinder::find
     */
    public function testFind()
    {
        $finder = new PopularProductsFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
