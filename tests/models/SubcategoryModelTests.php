<?php

namespace app\test\models;

use app\models\SubcategoryModel;

/**
 * Тестирует SubcategoryModel
 */
class SubcategoryModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_seocode = 'Some seocode';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\SubcategoryModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new SubcategoryModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'seocode'));
        $this->assertTrue(property_exists($model, 'id_categories'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'seocode'=>self::$_seocode, 'id_categories'=>self::$_id];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->seocode));
        $this->assertFalse(empty($model->id_categories));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_seocode, $model->seocode);
        $this->assertEquals(self::$_id, $model->id_categories);
    }
}
