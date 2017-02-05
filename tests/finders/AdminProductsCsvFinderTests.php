<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use yii\db\ActiveQuery;
use app\finders\AdminProductsCsvFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\filters\{AdminProductsFiltersInterface,
    AdminProductsFilters};
use app\collections\ProductsCollection;

/**
 * Тестирует класс AdminProductsCsvFinder
 */
class AdminProductsCsvFinderTests extends TestCase
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
     * Тестирует свойства AdminProductsCsvFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsCsvFinder::class);
        
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminProductsCsvFinder::setFilters
     * если передан неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filters = new class() {};
        
        $finder = new AdminProductsCsvFinder();
        $finder->setFilters($filters);
    }
    
    /**
     * Тестирует метод AdminProductsCsvFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends AdminProductsFilters {};
        
        $finder = new AdminProductsCsvFinder();
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(AdminProductsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsCsvFinder::find
     * если пуст AdminProductsCsvFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new AdminProductsCsvFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AdminProductsCsvFinder::find
     * если фильтры пусты
     */
    public function testFindEmptyPageFilters()
    {
        $filters = new class() extends AdminProductsFilters {};
        
        $finder = new AdminProductsCsvFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(ActiveQuery::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsCsvFinder::find
     * если фильтры не пусты
     */
    public function testFindEmptyPage()
    {
        $filters = new class() extends AdminProductsFilters {
            public $colors = [1, 3];
        };
        
        $finder = new AdminProductsCsvFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(ActiveQuery::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
