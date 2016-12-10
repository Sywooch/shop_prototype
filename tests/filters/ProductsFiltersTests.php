<?php

namespace app\tests\filters;

use PHPUnit\Framework\TestCase;
use app\filters\ProductsFilters;

/**
 * Тестирует класс ProductsFilters
 */
class ProductsFiltersTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(ProductsFilters::class);
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
    }
    
    /**
     * Тестирует метод ProductsFilters::rules
     */
    public function testRules()
    {
        $filter = new ProductsFilters();
        $filter->attributes = [
            'sortingField'=>'price',
            'sortingType'=>'SORT_ASC',
            'colors'=>[12, 4],
            'sizes'=>[3, 7],
            'brands'=>[2],
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('price', $result);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('SORT_ASC', $result);
        
        $reflection = new \ReflectionProperty($filter, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame([12, 4], $result);
        
        $reflection = new \ReflectionProperty($filter, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame([3, 7], $result);
        
        $reflection = new \ReflectionProperty($filter, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame([2], $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::setSortingField
     */
    public function testSetSortingField()
    {
        $filter = new ProductsFilters();
        $filter->setSortingField('date');
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame('date', $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::getSortingField
     */
    public function testGetSortingField()
    {
        $filter = new ProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'price');
        
        $result = $filter->getSortingField();
        
        $this->assertSame('price', $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::setSortingType
     */
    public function testSetSortingType()
    {
        $filter = new ProductsFilters();
        $filter->setSortingType('SORT_ASC');
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame('SORT_ASC', $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::getSortingType
     */
    public function testGetSortingType()
    {
        $filter = new ProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'SORT_DESC');
        
        $result = $filter->getSortingType();
        
        $this->assertSame('SORT_DESC', $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::setColors
     */
    public function testSetColors()
    {
        $filter = new ProductsFilters();
        $filter->setColors([12, 45]);
        
        $reflection = new \ReflectionProperty($filter, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([12, 45], $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::getColors
     */
    public function testGetColors()
    {
        $filter = new ProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [1, 4]);
        
        $result = $filter->getColors();
        
        $this->assertSame([1, 4], $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::setSizes
     */
    public function testSetSizes()
    {
        $filter = new ProductsFilters();
        $filter->setSizes([3, 15]);
        
        $reflection = new \ReflectionProperty($filter, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([3, 15], $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::getSizes
     */
    public function testGetSizes()
    {
        $filter = new ProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [1, 4]);
        
        $result = $filter->getSizes();
        
        $this->assertSame([1, 4], $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::setBrands
     */
    public function testSetBrands()
    {
        $filter = new ProductsFilters();
        $filter->setBrands([56, 1]);
        
        $reflection = new \ReflectionProperty($filter, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([56, 1], $result);
    }
    
    /**
     * Тестирует метод ProductsFilters::getBrands
     */
    public function testGetBrands()
    {
        $filter = new ProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 3);
        
        $result = $filter->getBrands();
        
        $this->assertSame(3, $result);
    }
}
