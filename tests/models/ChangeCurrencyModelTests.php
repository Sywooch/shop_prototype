<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\ChangeCurrencyModel;

/**
 * Тестирует класс app\models\ChangeCurrencyModel
 */
class ChangeCurrencyModelTests extends TestCase
{
    private static $_dbClass;
    public static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>'app\tests\sources\fixtures\CurrencyFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ChangeCurrencyModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ChangeCurrencyModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('CHANGE_CURRENCY'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        
        $model = new ChangeCurrencyModel(['scenario'=>ChangeCurrencyModel::CHANGE_CURRENCY]);
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
        $fixture = self::$_dbClass->currency['currency_1'];
        
        $model = new ChangeCurrencyModel(['scenario'=>ChangeCurrencyModel::CHANGE_CURRENCY]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new ChangeCurrencyModel(['scenario'=>ChangeCurrencyModel::CHANGE_CURRENCY]);
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
