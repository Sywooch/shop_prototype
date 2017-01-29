<?php

namespace app\tests\filters;

use PHPUnit\Framework\TestCase;
use app\filters\OrdersFilters;

/**
 * Тестирует класс OrdersFilters
 */
class OrdersFiltersTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(OrdersFilters::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('status'));
        $this->assertTrue($reflection->hasProperty('datesInterval'));
    }
    
    /**
     * Тестирует метод OrdersFilters::scenarios
     */
    public function testScenarios()
    {
        $filter = new OrdersFilters(['scenario'=>OrdersFilters::SESSION]);
        $filter->attributes = [
            'sortingType'=>SORT_ASC,
            'status'=>'shipped',
            'datesInterval'=>1,
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(SORT_ASC, (int) $result);
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('shipped', $result);
        
        $reflection = new \ReflectionProperty($filter, 'datesInterval');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(1, $result);
    }
    
    /**
     * Тестирует метод OrdersFilters::setSortingType
     */
    public function testSetSortingType()
    {
        $filter = new OrdersFilters();
        $filter->setSortingType(SORT_ASC);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame(SORT_ASC, (int) $result);
    }
    
    /**
     * Тестирует метод OrdersFilters::getSortingType
     */
    public function testGetSortingType()
    {
        $filter = new OrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, SORT_DESC);
        
        $result = $filter->getSortingType();
        
        $this->assertSame(SORT_DESC, $result);
    }
    
    /**
     * Тестирует метод OrdersFilters::setStatus
     */
    public function testSetStatus()
    {
        $filter = new OrdersFilters();
        $filter->setStatus('shipped');
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame('shipped', $result);
    }
    
    /**
     * Тестирует метод OrdersFilters::getStatus
     */
    public function testGetColors()
    {
        $filter = new OrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'shipped');
        
        $result = $filter->getStatus();
        
        $this->assertSame('shipped', $result);
    }
    
    /**
     * Тестирует метод OrdersFilters::setDatesInterval
     */
    public function testSetDatesInterval()
    {
        $filter = new OrdersFilters();
        $filter->setDatesInterval(1);
        
        $reflection = new \ReflectionProperty($filter, 'datesInterval');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame(1, $result);
    }
    
    /**
     * Тестирует метод OrdersFilters::getDatesInterval
     */
    public function testGetDatesInterval()
    {
        $filter = new OrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'datesInterval');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 1);
        
        $result = $filter->getDatesInterval();
        
        $this->assertSame(1, $result);
    }
    
    /**
     * Тестирует метод OrdersFilters::fields
     */
    public function testFields()
    {
        $filter = new OrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, SORT_ASC);
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'canceled');
        
        $reflection = new \ReflectionProperty($filter, 'datesInterval');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 1);
        
        $result = $filter->toArray();
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('datesInterval', $result);
        
        $this->assertSame(SORT_ASC, $result['sortingType']);
        $this->assertSame('canceled', $result['status']);
        $this->assertSame(1, $result['datesInterval']);
    }
}
