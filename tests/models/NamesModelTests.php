<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\NamesModel;

/**
 * Тестирует класс app\models\NamesModel
 */
class NamesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'names'=>'app\tests\sources\fixtures\NamesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\NamesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\NamesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new NamesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->names['name_1'];
        
        $model = new NamesModel(['scenario'=>NamesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'name'=>$fixture['name'], 
        ];
        
        $this->assertEquals($fixture['name'], $model->name);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->names['name_2'];
        
        $model = new NamesModel(['scenario'=>NamesModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        
        $model = new NamesModel(['scenario'=>NamesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'name'=>$fixture['name'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $namesQuery = NamesModel::find();
        $namesQuery->extendSelect(['id', 'name']);
        
        $queryRaw = clone $namesQuery;
        
        $expectedQuery = "SELECT `names`.`id`, `names`.`name` FROM `names`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $namesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof NamesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->names['name_1'];
        
        $namesQuery = NamesModel::find();
        $namesQuery->extendSelect(['id', 'name']);
        $namesQuery->where(['[[names.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $namesQuery;
        
        $expectedQuery = sprintf("SELECT `names`.`id`, `names`.`name` FROM `names` WHERE `names`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $namesQuery->one();
        
        $this->assertTrue($result instanceof NamesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
