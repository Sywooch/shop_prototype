<?php

namespace app\test\models;

use app\models\SizesModel;

/**
 * Тестирует SizesModel
 */
class SizesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_size = '46';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\SizesModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new SizesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'size'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'size'=>self::$_size];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->size));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_size, $model->size);
    }
}
