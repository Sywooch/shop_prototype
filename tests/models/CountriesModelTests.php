<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\CountriesModel;

/**
 * Тестирует класс app\models\CountriesModel
 */
class CountriesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'countries'=>'app\tests\sources\fixtures\CountriesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\CountriesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\CountriesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new CountriesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('country', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->countries['country_1'];
        
        $model = new CountriesModel(['scenario'=>CountriesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'country'=>$fixture['country'], 
        ];
        
        $this->assertEquals($fixture['country'], $model->country);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->countries['country_2'];
        
        $model = new CountriesModel(['scenario'=>CountriesModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('country', $model->errors));
        
        $model = new CountriesModel(['scenario'=>CountriesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'country'=>$fixture['country'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $countriesQuery = CountriesModel::find();
        $countriesQuery->extendSelect(['id', 'country']);
        
        $queryRaw = clone $countriesQuery;
        
        $expectedQuery = "SELECT `countries`.`id`, `countries`.`country` FROM `countries`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $countriesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof CountriesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->countries['country_1'];
        
        $countriesQuery = CountriesModel::find();
        $countriesQuery->extendSelect(['id', 'country']);
        $countriesQuery->where(['[[countries.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $countriesQuery;
        
        $expectedQuery = sprintf("SELECT `countries`.`id`, `countries`.`country` FROM `countries` WHERE `countries`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $countriesQuery->one();
        
        $this->assertTrue($result instanceof CountriesModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->countries['country_1'];
        $fixture2 = self::$_dbClass->countries['country_2'];
        
        $countriesQuery = CountriesModel::find();
        $countriesQuery->extendSelect(['id', 'country']);
        $countriesArray = $countriesQuery->allMap('id', 'country');
        
        $this->assertFalse(empty($countriesArray));
        $this->assertTrue(array_key_exists($fixture['id'], $countriesArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $countriesArray));
        $this->assertTrue(in_array($fixture['country'], $countriesArray));
        $this->assertTrue(in_array($fixture2['country'], $countriesArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
