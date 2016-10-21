<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
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
                'colors'=>'app\tests\sources\fixtures\ColorsFixture',
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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT'));
        
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
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT]);
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
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [
            'id'=>$fixture['id'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $colorsQuery = ColorsModel::find();
        $colorsQuery->extendSelect(['id', 'color']);
        
        $queryRaw = clone $colorsQuery;
        
        $expectedQuery = "SELECT `colors`.`id`, `colors`.`color` FROM `colors`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $colorsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ColorsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->colors['color_1'];
        
        $colorsQuery = ColorsModel::find();
        $colorsQuery->extendSelect(['id', 'color']);
        $colorsQuery->where(['colors.color'=>$fixture['color']]);
        
        $queryRaw = clone $colorsQuery;
        
        $expectedQuery = sprintf("SELECT `colors`.`id`, `colors`.`color` FROM `colors` WHERE `colors`.`color`='%s'", $fixture['color']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $colorsQuery->one();
        
        $this->assertTrue($result instanceof ColorsModel);
    }
    
    /**
     * Тестирует метод ColorsModel::allMap
     */
    public function testAllMap()
    {
        $fixture1 = self::$_dbClass->colors['color_1'];
        $fixture2 = self::$_dbClass->colors['color_2'];
        
        $result = ColorsModel::allMap('id', 'color');
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(array_key_exists($fixture1['id'], $result));
        $this->assertTrue(array_key_exists($fixture2['id'], $result));
        $this->assertTrue(in_array($fixture1['color'], $result));
        $this->assertTrue(in_array($fixture2['color'], $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
