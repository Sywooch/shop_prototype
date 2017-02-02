<?php

namespace app\tests\filters;

use PHPUnit\Framework\TestCase;
use app\filters\AdminProductsFilters;

/**
 * Тестирует класс AdminProductsFilters
 */
class AdminProductsFiltersTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(AdminProductsFilters::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('active'));
    }
    
    /**
     * Тестирует метод AdminProductsFilters::scenarios
     */
    public function testScenarios()
    {
        $filter = new AdminProductsFilters(['scenario'=>AdminProductsFilters::SESSION]);
        $filter->attributes = [
            'sortingField'=>'price',
            'sortingType'=>SORT_ASC,
            'colors'=>[12, 4],
            'sizes'=>[3, 7],
            'brands'=>[2],
            'categories'=>[1, 2],
            'subcategory'=>[2],
            'active'=>true,
        ];
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame('price', $result);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(SORT_ASC, (int) $result);
        
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
        
        $reflection = new \ReflectionProperty($filter, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame([1, 2], $result);
        
        $reflection = new \ReflectionProperty($filter, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame([2], $result);
        
        $reflection = new \ReflectionProperty($filter, 'active');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        $this->assertSame(true, $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setSortingField
     */
    public function testSetSortingField()
    {
        $filter = new AdminProductsFilters();
        $filter->setSortingField('date');
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame('date', $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getSortingField
     */
    public function testGetSortingField()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'price');
        
        $result = $filter->getSortingField();
        
        $this->assertSame('price', $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setSortingType
     */
    public function testSetSortingType()
    {
        $filter = new AdminProductsFilters();
        $filter->setSortingType(SORT_ASC);
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame(SORT_ASC, (int) $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getSortingType
     */
    public function testGetSortingType()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, SORT_DESC);
        
        $result = $filter->getSortingType();
        
        $this->assertSame(SORT_DESC, $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setColors
     */
    public function testSetColors()
    {
        $filter = new AdminProductsFilters();
        $filter->setColors([12, 45]);
        
        $reflection = new \ReflectionProperty($filter, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([12, 45], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getColors
     */
    public function testGetColors()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [1, 4]);
        
        $result = $filter->getColors();
        
        $this->assertSame([1, 4], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setSizes
     */
    public function testSetSizes()
    {
        $filter = new AdminProductsFilters();
        $filter->setSizes([3, 15]);
        
        $reflection = new \ReflectionProperty($filter, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([3, 15], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getSizes
     */
    public function testGetSizes()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [1, 4]);
        
        $result = $filter->getSizes();
        
        $this->assertSame([1, 4], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setBrands
     */
    public function testSetBrands()
    {
        $filter = new AdminProductsFilters();
        $filter->setBrands([56, 1]);
        
        $reflection = new \ReflectionProperty($filter, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([56, 1], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getBrands
     */
    public function testGetBrands()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [3]);
        
        $result = $filter->getBrands();
        
        $this->assertSame([3], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setCategories
     */
    public function testSetCategories()
    {
        $filter = new AdminProductsFilters();
        $filter->setCategories([56, 1]);
        
        $reflection = new \ReflectionProperty($filter, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([56, 1], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getCategories
     */
    public function testGetCategories()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [3]);
        
        $result = $filter->getCategories();
        
        $this->assertSame([3], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setSubcategory
     */
    public function testSetSubcategory()
    {
        $filter = new AdminProductsFilters();
        $filter->setSubcategory([56, 1]);
        
        $reflection = new \ReflectionProperty($filter, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertSame([56, 1], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getSubcategory
     */
    public function testGetSubcategory()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [2, 3]);
        
        $result = $filter->getSubcategory();
        
        $this->assertSame([2, 3], $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::setActive
     */
    public function testSetActive()
    {
        $filter = new AdminProductsFilters();
        $filter->setActive(true);
        
        $reflection = new \ReflectionProperty($filter, 'active');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($filter);
        
        $this->assertEquals(true, $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::getActive
     */
    public function testGetActive()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'active');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, false);
        
        $result = $filter->getActive();
        
        $this->assertEquals(false, $result);
    }
    
    /**
     * Тестирует метод AdminProductsFilters::fields
     */
    public function testFields()
    {
        $filter = new AdminProductsFilters();
        
        $reflection = new \ReflectionProperty($filter, 'sortingField');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, 'price');
        
        $reflection = new \ReflectionProperty($filter, 'sortingType');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, SORT_ASC);
        
        $reflection = new \ReflectionProperty($filter, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [2, 14, 4]);
        
        $reflection = new \ReflectionProperty($filter, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [1, 3]);
        
        $reflection = new \ReflectionProperty($filter, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [12]);
        
        $reflection = new \ReflectionProperty($filter, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [3]);
        
        $reflection = new \ReflectionProperty($filter, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, [2, 3]);
        
        $reflection = new \ReflectionProperty($filter, 'active');
        $reflection->setAccessible(true);
        $reflection->setValue($filter, true);
        
        $result = $filter->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('sortingField', $result);
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertSame('price', $result['sortingField']);
        $this->assertSame(SORT_ASC, $result['sortingType']);
        $this->assertSame([2, 14, 4], $result['colors']);
        $this->assertSame([1, 3], $result['sizes']);
        $this->assertSame([12], $result['brands']);
        
        $this->assertSame([3], $result['categories']);
        $this->assertSame([2, 3], $result['subcategory']);
        $this->assertSame(true, $result['active']);
    }
}
