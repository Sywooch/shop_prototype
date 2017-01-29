<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AccountOrdersFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\filters\{OrdersFiltersInterface,
    OrdersFilters};
use app\collections\PurchasesCollection;

/**
 * Тестирует класс AccountOrdersFinder
 */
class AccountOrdersFinderTests extends TestCase
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
     * Тестирует свойства AccountOrdersFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountOrdersFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_user'));
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::setId_user
     * если передан неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetId_userError()
    {
        $id_user = null;
        
        $finder = new AccountOrdersFinder();
        $finder->setId_user($id_user);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::setId_user
     */
    public function testSetId_user()
    {
        $id_user = 2;
        
        $finder = new AccountOrdersFinder();
        $finder->setId_user($id_user);
        
        $reflection = new \ReflectionProperty($finder, 'id_user');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::setFilters
     * если передан неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filters = new class() {};
        
        $finder = new AccountOrdersFinder();
        $finder->setFilters($filters);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends OrdersFilters {};
        
        $finder = new AccountOrdersFinder();
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(OrdersFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::setPage
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPageError()
    {
        $page = null;
        
        $finder = new AccountOrdersFinder();
        $finder->setPage($page);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::setPage
     */
    public function testSetPage()
    {
        $page = 2;
        
        $finder = new AccountOrdersFinder();
        $finder->setPage($page);
        
        $reflection = new \ReflectionProperty($finder, 'page');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('integer', (int) $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::find
     * если пуст AccountOrdersFinder::id_user
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_user
     */
    public function testFindEmptyId_user()
    {
        $finder = new AccountOrdersFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::find
     * если пуст AccountOrdersFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new AccountOrdersFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_user');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 2);
        
        $finder->find();
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::find
     * если фильтры пусты
     * страница === 0
     */
    public function testFindEmptyPageFilters()
    {
        $filters = new class() extends OrdersFilters {};
        
        $finder = new AccountOrdersFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_user');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::find
     * если фильтры не пусты
     * страница === 0
     */
    public function testFindEmptyPage()
    {
        $filters = new class() extends OrdersFilters {
            public $sortingType = SORT_ASC;
        };
        
        $finder = new AccountOrdersFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_user');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFinder::find
     * если фильтры пусты
     * страница !== 0
     */
    public function testFindEmptyFilters2()
    {
        $filters = new class() extends OrdersFilters {};
        
        $finder = new AccountOrdersFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_user');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
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
