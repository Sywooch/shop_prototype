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
        $this->assertTrue($reflection->hasProperty('dateFrom'));
        $this->assertTrue($reflection->hasProperty('dateTo'));
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
            'dateFrom'=>time(),
            'dateTo'=>time(),
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(SORT_ASC, (int) $result);
        
        $reflection = new \ReflectionProperty($filter, 'status');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('shipped', $result);
        
        $reflection = new \ReflectionProperty($filter, 'dateFrom');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(time(), $result);
        
        $reflection = new \ReflectionProperty($filter, 'dateTo');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(time(), $result);
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
     * Тестирует метод OrdersFilters::setDateFrom
     * передаю неверное значение
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: dateFrom
     */
    public function testSetDateFromError()
    {
        $filter = new OrdersFilters();
        $filter->setDateFrom((int) mb_substr(time(), 0, 8, 'UTF-8'));
    }
    
    /**
     * Тестирует метод OrdersFilters::setDateFrom
     */
    public function testSetDateFrom()
    {
        $filter = new OrdersFilters();
        $filter->setDateFrom(time());
        
        $reflection = new \ReflectionProperty($filter, 'dateFrom');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertInternalType('integer', $result);
        $this->assertEquals(10, mb_strlen($result, 'UTF-8'));
    }
    
    /**
     * Тестирует метод OrdersFilters::getDateFrom
     */
    public function testGetDateFrom()
    {
        $filter = new OrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'dateFrom');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, time());
        
        $result = $filter->getDateFrom();
        
        $this->assertInternalType('integer', $result);
        $this->assertEquals(10, mb_strlen($result, 'UTF-8'));
    }
    
    /**
     * Тестирует метод OrdersFilters::setDateTo
     * передаю неверное значение
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: dateTo
     */
    public function testSetDateToError()
    {
        $filter = new OrdersFilters();
        $filter->setDateTo((int) mb_substr(time(), 0, 8, 'UTF-8'));
    }
    
    /**
     * Тестирует метод OrdersFilters::setDateTo
     */
    public function testSetDateTo()
    {
        $filter = new OrdersFilters();
        $filter->setDateTo(time());
        
        $reflection = new \ReflectionProperty($filter, 'dateTo');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertInternalType('integer', $result);
        $this->assertEquals(10, mb_strlen($result, 'UTF-8'));
    }
    
    /**
     * Тестирует метод OrdersFilters::getDateTo
     */
    public function testGetDateTo()
    {
        $filter = new OrdersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'dateTo');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, time());
        
        $result = $filter->getDateTo();
        
        $this->assertInternalType('integer', $result);
        $this->assertEquals(10, mb_strlen($result, 'UTF-8'));
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
        
        $reflection = new \ReflectionProperty($filter, 'dateFrom');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, time());
        
        $reflection = new \ReflectionProperty($filter, 'dateTo');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, time());
        
        $result = $filter->toArray();
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('dateFrom', $result);
        $this->assertArrayHasKey('dateTo', $result);
        
        $this->assertSame(SORT_ASC, $result['sortingType']);
        $this->assertSame('canceled', $result['status']);
        $this->assertSame(time(), $result['dateFrom']);
        $this->assertSame(time(), $result['dateTo']);
    }
}
