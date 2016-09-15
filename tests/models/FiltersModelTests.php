<?php

namespace app\test\models;

use PHPUnit\Framework\TestCase;
use app\models\FiltersModel;

/**
 * Тестирует FiltersModel
 */
class FiltersModelTests extends TestCase
{
    private static $_reflectionClass;
    private static $_colors = [1];
    private static $_sizes = [1,3];
    private static $_brands = [2,3];
    private static $_sortingField = 'price';
    private static $_sortingType = 'ASC';
    private static $_category = 'Some categories';
    private static $_subcategory = 'Some subcategory';
    private static $_search = 'Some search';
    private static $_getActive = false;
    private static $_getNotActive = true;
    
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
        $this->assertTrue(property_exists($model, 'category'));
        $this->assertTrue(property_exists($model, 'subcategory'));
        $this->assertTrue(property_exists($model, 'search'));
        $this->assertTrue(property_exists($model, 'getActive'));
        $this->assertTrue(property_exists($model, 'getNotActive'));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new FiltersModel();
        $model->attributes = [
            'colors'=>self::$_colors, 
            'sizes'=>self::$_sizes, 
            'brands'=>self::$_brands, 
            'sortingField'=>self::$_sortingField, 
            'sortingType'=>self::$_sortingType, 
            'category'=>self::$_category, 
            'subcategory'=>self::$_subcategory, 
            'search'=>self::$_search, 
            'getActive'=>self::$_getActive, 
            'getNotActive'=>self::$_getNotActive
        ];
        
        $this->assertEquals(self::$_colors, $model->colors);
        $this->assertEquals(self::$_sizes, $model->sizes);
        $this->assertEquals(self::$_brands, $model->brands);
        $this->assertEquals(self::$_sortingField, $model->sortingField);
        $this->assertEquals(self::$_sortingType, $model->sortingType);
        $this->assertEquals(self::$_category, $model->category);
        $this->assertEquals(self::$_subcategory, $model->subcategory);
        $this->assertEquals(self::$_search, $model->search);
        $this->assertEquals(self::$_getActive, $model->getActive);
        $this->assertEquals(self::$_getNotActive, $model->getNotActive);
    }
    
    /**
     * Тестирует метод FiltersModel::clean
     */
    public function testClean()
    {
        $model = new FiltersModel();
        $model->attributes = [
            'colors'=>self::$_colors, 
            'sizes'=>self::$_sizes, 
            'brands'=>self::$_brands, 
            'sortingField'=>self::$_sortingField, 
            'sortingType'=>self::$_sortingType, 
            'getActive'=>self::$_getActive, 
            'getNotActive'=>self::$_getNotActive
        ];
        
        $this->assertFalse(empty($model->colors));
        $this->assertFalse(empty($model->sizes));
        $this->assertFalse(empty($model->brands));
        $this->assertFalse(empty($model->sortingField));
        $this->assertFalse(empty($model->sortingType));
        $this->assertTrue(empty($model->getActive));
        $this->assertFalse(empty($model->getNotActive));
        
        $model->clean();
        
        $this->assertTrue(empty($model->colors));
        $this->assertTrue(empty($model->sizes));
        $this->assertTrue(empty($model->brands));
        $this->assertTrue(empty($model->sortingField));
        $this->assertTrue(empty($model->sortingType));
        $this->assertFalse(empty($model->getActive));
        $this->assertFalse(empty($model->getNotActive));
    }
    
    /**
     * Тестирует метод FiltersModel::cleanOther
     */
    public function testCleanOther()
    {
        $model = new FiltersModel();
        $model->attributes = [
            'category'=>self::$_category, 
            'subcategory'=>self::$_subcategory, 
            'search'=>self::$_search
        ];
        
        $this->assertFalse(empty($model->category));
        $this->assertFalse(empty($model->subcategory));
        $this->assertFalse(empty($model->search));
        
        $model->cleanOther();
        
        $this->assertTrue(empty($model->category));
        $this->assertTrue(empty($model->subcategory));
        $this->assertTrue(empty($model->search));
    }
}
