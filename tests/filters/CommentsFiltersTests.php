<?php

namespace app\tests\filters;

use PHPUnit\Framework\TestCase;
use app\filters\CommentsFilters;

/**
 * Тестирует класс CommentsFilters
 */
class CommentsFiltersTests extends TestCase
{
    private $filter;
    
    public function setUp()
    {
        $this->filter = new CommentsFilters();
    }
    
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(CommentsFilters::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('activeStatus'));
    }
    
    /**
     * Тестирует метод CommentsFilters::scenarios
     */
    public function testScenarios()
    {
        $filter = new CommentsFilters(['scenario'=>CommentsFilters::SESSION]);
        $filter->attributes = [
            'sortingField'=>'price',
            'sortingType'=>SORT_ASC,
            'activeStatus'=>1,
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('price', $result);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(SORT_ASC, (int) $result);
        
        $reflection = new \ReflectionProperty($filter, 'activeStatus');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(1, (int) $result);
    }
    
    /**
     * Тестирует метод CommentsFilters::setSortingField
     */
    public function testSetSortingField()
    {
        $this->filter->setSortingField('date');
        
        $reflection = new \ReflectionProperty($this->filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->filter);
        
        $this->assertSame('date', $result);
    }
    
    /**
     * Тестирует метод CommentsFilters::getSortingField
     */
    public function testGetSortingField()
    {
        $reflection = new \ReflectionProperty($this->filter, 'sortingField');
        $reflection->setAccessible(true);
        $reflection->setValue($this->filter, 'date');
        
        $result = $this->filter->getSortingField();
        
        $this->assertSame('date', $result);
    }
    
    /**
     * Тестирует метод CommentsFilters::setSortingType
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
     * Тестирует метод CommentsFilters::getSortingType
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
     * Тестирует метод CommentsFilters::setActiveStatus
     */
    public function testSetActiveStatus()
    {
        $this->filter->setActiveStatus(1);
        
        $reflection = new \ReflectionProperty($this->filter, 'activeStatus');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->filter);
        
        $this->assertEquals(1, $result);
    }
    
    /**
     * Тестирует метод CommentsFilters::getActiveStatus
     */
    public function testGetActiveStatus()
    {
        $reflection = new \ReflectionProperty($this->filter, 'activeStatus');
        $reflection->setAccessible(true);
        $reflection->setValue($this->filter, 1);
        
        $result = $this->filter->getActiveStatus();
        
        $this->assertSame(1, $result);
    }
}
