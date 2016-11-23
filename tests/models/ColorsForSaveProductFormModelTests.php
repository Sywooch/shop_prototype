<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\ColorsForSaveProductFormModel;

/**
 * Тестирует класс app\models\ColorsForSaveProductFormModel
 */
class ColorsForSaveProductFormModelTests extends TestCase
{
    private static $_dbClass;
    public static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>'app\tests\sources\fixtures\ColorsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ColorsForSaveProductFormModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ColorsForSaveProductFormModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('SAVE'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->colors['color_1'];
        
        $model = new ColorsForSaveProductFormModel(['scenario'=>ColorsForSaveProductFormModel::SAVE]);
        $model->attributes = [
            'id'=>$fixture['id'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->colors['color_1'];
        
        $model = new ColorsForSaveProductFormModel(['scenario'=>ColorsForSaveProductFormModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new ColorsForSaveProductFormModel(['scenario'=>ColorsForSaveProductFormModel::SAVE]);
        $model->attributes = [
            'id'=>$fixture['id'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
