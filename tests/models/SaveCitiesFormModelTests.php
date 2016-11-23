<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\SaveCitiesFormModel;

/**
 * Тестирует класс app\models\SaveCitiesFormModel
 */
class SaveCitiesFormModelTests extends TestCase
{
    private static $_dbClass;
    public static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'cities'=>'app\tests\sources\fixtures\CitiesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\SaveCitiesFormModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\SaveCitiesFormModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('SAVE'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('city'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->city['city_1'];
        
        $model = new SaveCitiesFormModel(['scenario'=>SaveCitiesFormModel::SAVE]);
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
        $fixture = self::$_dbClass->cities['city_1'];
        
        $model = new SaveCitiesFormModel(['scenario'=>SaveCitiesFormModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('city', $model->errors));
        
        $model = new SaveCitiesFormModel(['scenario'=>SaveCitiesFormModel::SAVE]);
        $model->attributes = [
            'city'=>$fixture['city'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
