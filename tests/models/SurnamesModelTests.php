<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\SurnamesModel;

/**
 * Тестирует класс app\models\SurnamesModel
 */
class SurnamesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'surnames'=>'app\tests\sources\fixtures\SurnamesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\SurnamesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\SurnamesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new SurnamesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('surname', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->surnames['surname_1'];
        
        $model = new SurnamesModel(['scenario'=>SurnamesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'surname'=>$fixture['surname'], 
        ];
        
        $this->assertEquals($fixture['surname'], $model->surname);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->surnames['surname_2'];
        
        $model = new SurnamesModel(['scenario'=>SurnamesModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('surname', $model->errors));
        
        $model = new SurnamesModel(['scenario'=>SurnamesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'surname'=>$fixture['surname'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $surnamesQuery = SurnamesModel::find();
        $surnamesQuery->extendSelect(['id', 'surname']);
        
        $queryRaw = clone $surnamesQuery;
        
        $expectedQuery = "SELECT `surnames`.`id`, `surnames`.`surname` FROM `surnames`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $surnamesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof SurnamesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->surnames['surname_1'];
        
        $surnamesQuery = SurnamesModel::find();
        $surnamesQuery->extendSelect(['id', 'surname']);
        $surnamesQuery->where(['[[surnames.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $surnamesQuery;
        
        $expectedQuery = sprintf("SELECT `surnames`.`id`, `surnames`.`surname` FROM `surnames` WHERE `surnames`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $surnamesQuery->one();
        
        $this->assertTrue($result instanceof SurnamesModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->surnames['surname_1'];
        $fixture2 = self::$_dbClass->surnames['surname_2'];
        
        $surnamesQuery = SurnamesModel::find();
        $surnamesQuery->extendSelect(['id', 'surname']);
        $surnamesArray = $surnamesQuery->allMap('id', 'surname');
        
        $this->assertFalse(empty($surnamesArray));
        $this->assertTrue(array_key_exists($fixture['id'], $surnamesArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $surnamesArray));
        $this->assertTrue(in_array($fixture['surname'], $surnamesArray));
        $this->assertTrue(in_array($fixture2['surname'], $surnamesArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
