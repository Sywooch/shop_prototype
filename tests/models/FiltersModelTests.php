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
    private static $_categories = 'Some categories';
    private static $_subcategory = 'Some subcategory';
    private static $_search = 'Some search';
    
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
        $this->assertTrue(property_exists($model, 'categories'));
        $this->assertTrue(property_exists($model, 'subcategory'));
        $this->assertTrue(property_exists($model, 'search'));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new FiltersModel();
        $model->attributes = ['colors'=>self::$_colors, 'sizes'=>self::$_sizes, 'brands'=>self::$_brands, 'categories'=>self::$_categories, 'subcategory'=>self::$_subcategory, 'search'=>self::$_search];
        
        $this->assertFalse(empty($model->colors));
        $this->assertFalse(empty($model->sizes));
        $this->assertFalse(empty($model->brands));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertFalse(empty($model->search));
        
        $this->assertEquals(self::$_colors, $model->colors);
        $this->assertEquals(self::$_sizes, $model->sizes);
        $this->assertEquals(self::$_brands, $model->brands);
        $this->assertEquals(self::$_categories, $model->categories);
        $this->assertEquals(self::$_subcategory, $model->subcategory);
        $this->assertEquals(self::$_search, $model->search);
    }
}
