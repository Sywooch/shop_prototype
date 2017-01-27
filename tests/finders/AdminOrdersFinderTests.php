<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminOrdersFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\filters\{AdminOrdersFiltersInterface,
    AdminOrdersFilters};
use app\collections\PurchasesCollection;

/**
 * Тестирует класс AdminOrdersFinder
 */
class AdminOrdersFinderTests extends TestCase
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
     * Тестирует свойства AdminOrdersFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersFinder::class);
        
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminOrdersFinder::setFilters
     * если передан неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filters = new class() {};
        
        $finder = new AdminOrdersFinder();
        $finder->setFilters($filters);
    }
    
    /**
     * Тестирует метод AdminOrdersFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends AdminOrdersFilters {};
        
        $finder = new AdminOrdersFinder();
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(AdminOrdersFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFinder::setPage
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPageError()
    {
        $page = null;
        
        $widget = new AdminOrdersFinder();
        $widget->setPage($page);
    }
    
    /**
     * Тестирует метод AdminOrdersFinder::setPage
     */
    public function testSetPage()
    {
        $page = 2;
        
        $widget = new AdminOrdersFinder();
        $widget->setPage($page);
        
        $reflection = new \ReflectionProperty($widget, 'page');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', (int) $result);
    }
    
     /**
     * Тестирует метод AdminOrdersFinder::find
     * если пуст AdminOrdersFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new AdminOrdersFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AdminOrdersFinder::find
     * если фильтры пусты
     * страница === 0
     */
    public function testFindEmptyPageFilters()
    {
        $filters = new class() extends AdminOrdersFilters {};
        
        $finder = new AdminOrdersFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFinder::find
     * если фильтры не пусты
     * страница === 0
     */
    public function testFindEmptyPage()
    {
        $filters = new class() extends AdminOrdersFilters {
            public $status = 'shipped';
        };
        
        $finder = new AdminOrdersFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFinder::find
     * если фильтры пусты
     * страница !== 0
     */
    public function testFindEmptyFilters2()
    {
        $filters = new class() extends AdminOrdersFilters {};
        
        $finder = new AdminOrdersFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'page');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 2);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
