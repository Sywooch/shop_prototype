<?php

namespace app\tests\filters;

use PHPUnit\Framework\TestCase;
use app\filters\AdminOrdersFilters;

/**
 * Тестирует класс AdminOrdersFilters
 */
class AdminOrdersFiltersTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(AdminOrdersFilters::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('status'));
    }
    
    /**
     * Тестирует метод AdminOrdersFilters::scenarios
     */
    public function testScenarios()
    {
        $filter = new AdminOrdersFilters(['scenario'=>AdminOrdersFilters::SESSION]);
        $filter->attributes = [
            'sortingType'=>SORT_ASC,
            'status'=>'shipped',
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(SORT_ASC, (int) $result);
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('shipped', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFilters::setSortingType
     */
    public function testSetSortingType()
    {
        $filter = new AdminOrdersFilters();
        $filter->setSortingType(SORT_ASC);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame(SORT_ASC, (int) $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFilters::getSortingType
     */
    public function testGetSortingType()
    {
        $filter = new AdminOrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, SORT_DESC);
        
        $result = $filter->getSortingType();
        
        $this->assertSame(SORT_DESC, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFilters::setStatus
     */
    public function testSetStatus()
    {
        $filter = new AdminOrdersFilters();
        $filter->setStatus('shipped');
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame('shipped', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFilters::getStatus
     */
    public function testGetColors()
    {
        $filter = new AdminOrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'shipped');
        
        $result = $filter->getStatus();
        
        $this->assertSame('shipped', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFilters::fields
     */
    public function testFields()
    {
        $filter = new AdminOrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, SORT_ASC);
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'canceled');
        
        $result = $filter->toArray();
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('status', $result);
        
        $this->assertSame(SORT_ASC, $result['sortingType']);
        $this->assertSame('canceled', $result['status']);
    }
}
