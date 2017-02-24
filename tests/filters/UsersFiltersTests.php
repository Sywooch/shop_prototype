<?php

namespace app\tests\filters;

use PHPUnit\Framework\TestCase;
use app\filters\UsersFilters;

/**
 * Тестирует класс UsersFilters
 */
class UsersFiltersTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(UsersFilters::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
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
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('price', $result);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(SORT_ASC, (int) $result);
    }
    
    /**
     * Тестирует метод UsersFilters::setSortingField
     */
    public function testSetSortingField()
    {
        $filter = new UsersFilters();
        $filter->setSortingField('Name');
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame('Name', $result);
    }
    
    /**
     * Тестирует метод UsersFilters::getSortingField
     */
    public function testGetSortingField()
    {
        $filter = new UsersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'Orders');
        
        $result = $filter->getSortingField();
        
        $this->assertSame('Orders', $result);
    }
    
    /**
     * Тестирует метод UsersFilters::setSortingType
     */
    public function testSetSortingType()
    {
        $filter = new UsersFilters();
        $filter->setSortingType(SORT_ASC);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame(SORT_ASC, (int) $result);
    }
    
    /**
     * Тестирует метод UsersFilters::getSortingType
     */
    public function testGetSortingType()
    {
        $filter = new UsersFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, SORT_DESC);
        
        $result = $filter->getSortingType();
        
        $this->assertSame(SORT_DESC, $result);
    }
}
