<?php

namespace app\test\models;

use app\models\AdminMenuModel;

/**
 * Тестирует AdminMenuModel
 */
class AdminMenuModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 2;
    private static $_name = 'some name';
    private static $_route = 'some/index';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\AdminMenuModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('name'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('route'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new AdminMenuModel(['scenario'=>AdminMenuModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'route'=>self::$_route];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->route));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_route, $model->route);
    }
}
