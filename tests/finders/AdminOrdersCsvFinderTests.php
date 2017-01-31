<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminOrdersCsvFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\filters\{OrdersFiltersInterface,
    OrdersFilters};
use app\collections\PurchasesCollection;

/**
 * Тестирует класс AdminOrdersCsvFinder
 */
class AdminOrdersCsvFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminOrdersCsvFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersCsvFinder::class);
        
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminOrdersCsvFinder::setFilters
     * если передан неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filters = new class() {};
        
        $finder = new AdminOrdersCsvFinder();
        $finder->setFilters($filters);
    }
    
    /**
     * Тестирует метод AdminOrdersCsvFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends OrdersFilters {};
        
        $finder = new AdminOrdersCsvFinder();
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(OrdersFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersCsvFinder::find
     * если пуст AdminOrdersCsvFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new AdminOrdersCsvFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AdminOrdersCsvFinder::find
     * если фильтры пусты
     */
    public function testFindEmptyPageFilters()
    {
        $filters = new class() extends OrdersFilters {};
        
        $finder = new AdminOrdersCsvFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminOrdersCsvFinder::find
     * если фильтры не пусты
     */
    public function testFindEmptyPage()
    {
        $filters = new class() extends OrdersFilters {
            public $status = 'shipped';
        };
        
        $finder = new AdminOrdersCsvFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
