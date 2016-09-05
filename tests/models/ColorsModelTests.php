<?php

namespace app\tests\models;

use app\tests\DbManager;
use app\models\ColorsModel;

/**
 * Тестирует класс app\models\ColorsModel
 */
class ColorsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_color = 'black';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ColorsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ColorsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $model = new ColorsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('color', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_color, $model->color);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'color'=>self::$_color];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_color, $model->color);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
