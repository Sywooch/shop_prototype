<?php

namespace app\test\models;

use app\models\RulesModel;

/**
 * Тестирует RulesModel
 */
class RulesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_rule = 'Some rule';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\RulesModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new RulesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'rule'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new RulesModel(['scenario'=>RulesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'rule'=>self::$_rule];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->rule));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_rule, $model->rule);
    }
}
