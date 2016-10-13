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
    private static $_sortingField = 'price';
    private static $_sortingType = 'SORT_DESC';
    private static $_colors = [1];
    private static $_sizes = [1,3];
    private static $_brands = [2,3];
    
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
        
        self::$_reflectionClass->hasConstant('GET_FROM_FORM');
        
        self::$_reflectionClass->hasProperty('sortingField');
        self::$_reflectionClass->hasProperty('sortingType');
        self::$_reflectionClass->hasProperty('colors');
        self::$_reflectionClass->hasProperty('sizes');
        self::$_reflectionClass->hasProperty('brands');
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new FiltersModel(['scenario'=>FiltersModel::GET_FROM_FORM]);
        $model->attributes = [
            'sortingField'=>self::$_sortingField, 
            'sortingType'=>self::$_sortingType, 
            'colors'=>self::$_colors, 
            'sizes'=>self::$_sizes, 
            'brands'=>self::$_brands, 
        ];
        
        $this->assertEquals(self::$_sortingField, $model->sortingField);
        $this->assertEquals(self::$_sortingType, $model->sortingType);
        $this->assertEquals(self::$_colors, $model->colors);
        $this->assertEquals(self::$_sizes, $model->sizes);
        $this->assertEquals(self::$_brands, $model->brands);
    }
    
    /**
     * Тестирует метод FiltersModel::clean
     */
    public function testClean()
    {
        $model = new FiltersModel();
        $model->sortingField = self::$_sortingField;
        $model->sortingType = self::$_sortingType;
        $model->colors = self::$_colors;
        $model->sizes = self::$_sizes;
        $model->brands = self::$_brands;
        
        $this->assertFalse(empty($model->sortingField));
        $this->assertFalse(empty($model->sortingType));
        $this->assertFalse(empty($model->colors));
        $this->assertFalse(empty($model->sizes));
        $this->assertFalse(empty($model->brands));
        
        $model->clean();
        
        $this->assertTrue(empty($model->sortingField));
        $this->assertTrue(empty($model->sortingType));
        $this->assertTrue(empty($model->colors));
        $this->assertTrue(empty($model->sizes));
        $this->assertTrue(empty($model->brands));
    }
}
