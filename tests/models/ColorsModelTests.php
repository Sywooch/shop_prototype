<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\ColorsFixture;
use app\models\ColorsModel;

/**
 * Тестирует класс app\models\ColorsModel
 */
class ColorsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ColorsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ColorsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new ColorsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('color', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->colors['color_1'];
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'color'=>$fixture['color'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['color'], $model->color);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'color'=>$fixture['color'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['color'], $model->color);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
