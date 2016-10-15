<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\CurrencyModel;

/**
 * Тестирует класс app\models\CurrencyMode
 */
class CurrencyModelTests extends TestCase
{
    private static $_dbClass;
    public static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>'app\tests\source\fixtures\CurrencyFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\CurrencyModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\CurrencyModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_SESSION'));
        
        $model = new CurrencyModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('currency', $model->attributes));
        $this->assertTrue(array_key_exists('exchange_rate', $model->attributes));
        $this->assertTrue(array_key_exists('main', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'currency'=>$fixture['currency'],
            'exchange_rate'=>$fixture['exchange_rate'], 
            'main'=>$fixture['main'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['currency'], $model->currency);
        $this->assertEquals($fixture['exchange_rate'], $model->exchange_rate);
        $this->assertEquals($fixture['main'], $model->main);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'currency'=>$fixture['currency'],
            'exchange_rate'=>$fixture['exchange_rate'], 
            'main'=>$fixture['main'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['currency'], $model->currency);
        $this->assertEquals($fixture['exchange_rate'], $model->exchange_rate);
        $this->assertEquals($fixture['main'], $model->main);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_SESSION]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'currency'=>$fixture['currency'],
            'exchange_rate'=>$fixture['exchange_rate'], 
            'main'=>$fixture['main'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['currency'], $model->currency);
        $this->assertEquals($fixture['exchange_rate'], $model->exchange_rate);
        $this->assertEquals($fixture['main'], $model->main);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $currencyQuery = CurrencyModel::find();
        $currencyQuery->extendSelect(['id', 'currency', 'exchange_rate', 'main']);
        
        $queryRaw = clone $currencyQuery;
        
        $expectedQuery = "SELECT `currency`.`id`, `currency`.`currency`, `currency`.`exchange_rate`, `currency`.`main` FROM `currency`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $currencyQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof CurrencyModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        
        $currencyQuery = CurrencyModel::find();
        $currencyQuery->extendSelect(['id', 'currency', 'exchange_rate', 'main']);
        $currencyQuery->where(['[[currency.currency]]'=>$fixture['currency']]);
        
        $queryRaw = clone $currencyQuery;
        
        $expectedQuery = sprintf("SELECT `currency`.`id`, `currency`.`currency`, `currency`.`exchange_rate`, `currency`.`main` FROM `currency` WHERE `currency`.`currency`='%s'", $fixture['currency']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $currencyQuery->one();
        
        $this->assertTrue($result instanceof CurrencyModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
