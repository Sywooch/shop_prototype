<?php

namespace app\test\models;

use app\models\UsersRulesModel;

/**
 * Тестирует UsersRulesModel
 */
class UsersRulesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id_users = 2;
    private static $_id_rules = 3;
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\UsersRulesModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new UsersRulesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id_users'));
        $this->assertTrue(property_exists($model, 'id_rules'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new UsersRulesModel(['scenario'=>UsersRulesModel::GET_FROM_FORM]);
        $model->attributes = ['id_users'=>self::$_id_users, 'id_rules'=>self::$_id_rules];
        
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_rules));
        
        $this->assertEquals(self::$_id_users, $model->id_users);
        $this->assertEquals(self::$_id_rules, $model->id_rules);
        
        $model = new UsersRulesModel(['scenario'=>UsersRulesModel::GET_FROM_DB]);
        $model->attributes = ['id_users'=>self::$_id_users, 'id_rules'=>self::$_id_rules];
        
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_rules));
        
        $this->assertEquals(self::$_id_users, $model->id_users);
        $this->assertEquals(self::$_id_rules, $model->id_rules);
    }
}
