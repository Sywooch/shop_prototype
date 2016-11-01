<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\CitiesModel;

/**
 * Тестирует класс app\models\CitiesModel
 */
class CitiesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'cities'=>'app\tests\sources\fixtures\CitiesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\CitiesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\CitiesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new CitiesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('city', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->cities['city_1'];
        
        $model = new CitiesModel(['scenario'=>CitiesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'city'=>$fixture['city'], 
        ];
        
        $this->assertEquals($fixture['city'], $model->city);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->cities['city_2'];
        
        $model = new CitiesModel(['scenario'=>CitiesModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('city', $model->errors));
        
        $model = new CitiesModel(['scenario'=>CitiesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'city'=>$fixture['city'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $citiesQuery = CitiesModel::find();
        $citiesQuery->extendSelect(['id', 'city']);
        
        $queryRaw = clone $citiesQuery;
        
        $expectedQuery = "SELECT `cities`.`id`, `cities`.`city` FROM `cities`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $citiesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof CitiesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->cities['city_1'];
        
        $citiesQuery = CitiesModel::find();
        $citiesQuery->extendSelect(['id', 'city']);
        $citiesQuery->where(['[[cities.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $citiesQuery;
        
        $expectedQuery = sprintf("SELECT `cities`.`id`, `cities`.`city` FROM `cities` WHERE `cities`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $citiesQuery->one();
        
        $this->assertTrue($result instanceof CitiesModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->cities['city_1'];
        $fixture2 = self::$_dbClass->cities['city_2'];
        
        $citiesQuery = CitiesModel::find();
        $citiesQuery->extendSelect(['id', 'city']);
        $citiesArray = $citiesQuery->allMap('id', 'city');
        
        $this->assertFalse(empty($citiesArray));
        $this->assertTrue(array_key_exists($fixture['id'], $citiesArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $citiesArray));
        $this->assertTrue(in_array($fixture['city'], $citiesArray));
        $this->assertTrue(in_array($fixture2['city'], $citiesArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
