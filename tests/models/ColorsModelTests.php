<?php

namespace app\test\models;

use app\models\ColorsModel;

/**
 * Тестирует ColorsModel
 */
class ColorsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_color = 'Some Color';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\ColorsModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new ColorsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'color'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->color));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_color, $model->color);
    }
}