<?php

namespace app\test\models;

use app\models\FiltersModel;

/**
 * Тестирует FiltersModel
 */
class FiltersModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_colors = [1];
    private static $_sizes = [1,3];
    private static $_brands = [2,3];
    private static $_sortingField = 'price';
    private static $_sortingType = 'ASC';
    private static $_categories = 'Some categories';
    private static $_subcategory = 'Some subcategory';
    private static $_search = 'Some search';
    private static $_active = false;
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\FiltersModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new FiltersModel();
        
        $this->assertTrue(property_exists($model, 'colors'));
        $this->assertTrue(property_exists($model, 'sizes'));
        $this->assertTrue(property_exists($model, 'brands'));
        $this->assertTrue(property_exists($model, 'sortingField'));
        $this->assertTrue(property_exists($model, 'sortingType'));
        $this->assertTrue(property_exists($model, 'categories'));
        $this->assertTrue(property_exists($model, 'subcategory'));
        $this->assertTrue(property_exists($model, 'search'));
        $this->assertTrue(property_exists($model, 'active'));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new FiltersModel();
        $model->attributes = ['colors'=>self::$_colors, 'sizes'=>self::$_sizes, 'brands'=>self::$_brands, 'sortingField'=>self::$_sortingField, 'sortingType'=>self::$_sortingType, 'categories'=>self::$_categories, 'subcategory'=>self::$_subcategory, 'search'=>self::$_search, 'active'=>self::$_active];
        
        $this->assertFalse(empty($model->colors));
        $this->assertFalse(empty($model->sizes));
        $this->assertFalse(empty($model->brands));
        $this->assertFalse(empty($model->sortingField));
        $this->assertFalse(empty($model->sortingType));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertFalse(empty($model->search));
        $this->assertTrue(empty($model->active));
        
        $this->assertEquals(self::$_colors, $model->colors);
        $this->assertEquals(self::$_sizes, $model->sizes);
        $this->assertEquals(self::$_brands, $model->brands);
        $this->assertEquals(self::$_sortingField, $model->sortingField);
        $this->assertEquals(self::$_sortingType, $model->sortingType);
        $this->assertEquals(self::$_categories, $model->categories);
        $this->assertEquals(self::$_subcategory, $model->subcategory);
        $this->assertEquals(self::$_search, $model->search);
        $this->assertEquals(self::$_active, $model->active);
    }
    
    /**
     * Тестирует метод FiltersModel::clean
     */
    public function testClean()
    {
        $model = new FiltersModel();
        $model->attributes = ['colors'=>self::$_colors, 'sizes'=>self::$_sizes, 'brands'=>self::$_brands, 'sortingField'=>self::$_sortingField, 'sortingType'=>self::$_sortingType];
        
        $this->assertFalse(empty($model->colors));
        $this->assertFalse(empty($model->sizes));
        $this->assertFalse(empty($model->brands));
        $this->assertFalse(empty($model->sortingField));
        $this->assertFalse(empty($model->sortingType));
        
        $model->clean();
        
        $this->assertTrue(empty($model->colors));
        $this->assertTrue(empty($model->sizes));
        $this->assertTrue(empty($model->brands));
        $this->assertTrue(empty($model->sortingField));
        $this->assertTrue(empty($model->sortingType));
    }
    
    /**
     * Тестирует метод FiltersModel::cleanAdmin
     */
    public function testCleanAdmin()
    {
        $model = new FiltersModel();
        $model->attributes = ['categories'=>self::$_categories, 'subcategory'=>self::$_subcategory, 'active'=>self::$_active];
        
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertTrue(empty($model->active));
        
        $model->cleanAdmin();
        
        $this->assertTrue(empty($model->categories));
        $this->assertTrue(empty($model->subcategory));
        $this->assertFalse(empty($model->active));
    }
}
