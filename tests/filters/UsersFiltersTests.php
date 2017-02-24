<?php

namespace app\tests\filters;

use PHPUnit\Framework\TestCase;
use app\filters\UsersFilters;

/**
 * Тестирует класс UsersFilters
 */
class UsersFiltersTests extends TestCase
{
    private $filter;
    
    public function setUp()
    {
        $this->filter = new UsersFilters();
    }
    
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(UsersFilters::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('ordersStatus'));
    }
    
    /**
     * Тестирует метод UsersFilters::scenarios
     */
    public function testScenarios()
    {
        $filter = new UsersFilters(['scenario'=>UsersFilters::SESSION]);
        $filter->attributes = [
            'sortingField'=>'price',
            'sortingType'=>SORT_ASC,
            'ordersStatus'=>1,
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('price', $result);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(SORT_ASC, (int) $result);
        
        $reflection = new \ReflectionProperty($filter, 'ordersStatus');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(1, (int) $result);
    }
    
    /**
     * Тестирует метод UsersFilters::setSortingField
     */
    public function testSetSortingField()
    {
        $this->filter->setSortingField('Name');
        
        $reflection = new \ReflectionProperty($this->filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->filter);
        
        $this->assertSame('Name', $result);
    }
    
    /**
     * Тестирует метод UsersFilters::getSortingField
     */
    public function testGetSortingField()
    {
        $reflection = new \ReflectionProperty($this->filter, 'sortingField');
        $reflection->setAccessible(true);
        $reflection->setValue($this->filter, 'Orders');
        
        $result = $this->filter->getSortingField();
        
        $this->assertSame('Orders', $result);
    }
    
    /**
     * Тестирует метод UsersFilters::setSortingType
     */
    public function testSetSortingType()
    {
        $this->filter->setSortingType(SORT_ASC);
        
        $reflection = new \ReflectionProperty($this->filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->filter);
        
        $this->assertSame(SORT_ASC, (int) $result);
    }
    
    /**
     * Тестирует метод UsersFilters::getSortingType
     */
    public function testGetSortingType()
    {
        $reflection = new \ReflectionProperty($this->filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($this->filter, SORT_DESC);
        
        $result = $this->filter->getSortingType();
        
        $this->assertSame(SORT_DESC, $result);
    }
    
    /**
     * Тестирует метод UsersFilters::setOrdersStatus
     */
    public function testSetOrdersStatus()
    {
        $this->filter->setOrdersStatus(1);
        
        $reflection = new \ReflectionProperty($this->filter, 'ordersStatus');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->filter);
        
        $this->assertEquals(1, $result);
    }
    
    /**
     * Тестирует метод UsersFilters::getOrdersStatus
     */
    public function testGetOrdersStatus()
    {
        $reflection = new \ReflectionProperty($this->filter, 'ordersStatus');
        $reflection->setAccessible(true);
        $reflection->setValue($this->filter, 1);
        
        $result = $this->filter->getOrdersStatus();
        
        $this->assertSame(1, $result);
    }
}
