<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\CurrencyModel;
use app\helpers\MappersHelper;

/**
 * Тестирует CurrencyModel
 */
class CurrencyModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_currencyFresh = 'UAH';
    private static $_currency2 = 'USD';
    private static $_exchange_rate = '12.5698';
    private static $_main = true;
    private static $_mainFalse = false;
    private static $_message = 'Валюта с таким именем уже добавлена!';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\CurrencyModel');
        
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_main]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id + 1, ':currency'=>self::$_currency2, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_mainFalse]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new CurrencyModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_SET_CURRENCY'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_ADD'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_UPDATE'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_DELETE'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'currency'));
        $this->assertTrue(property_exists($model, 'exchange_rate'));
        $this->assertTrue(property_exists($model, 'main'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'currency'=>self::$_currency, 'exchange_rate'=>self::$_exchange_rate, 'main'=>self::$_main];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_currency, $model->currency);
        $this->assertEquals(self::$_exchange_rate, $model->exchange_rate);
        $this->assertEquals(self::$_main, $model->main);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_SET_CURRENCY]);
        $model->attributes = ['id'=>self::$_id];
        
        $this->assertEquals(self::$_id, $model->id);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_ADD]);
        $model->attributes = ['currency'=>self::$_currency, 'exchange_rate'=>self::$_exchange_rate, 'main'=>self::$_main];
        
        $this->assertEquals(self::$_currency, $model->currency);
        $this->assertEquals(self::$_exchange_rate, $model->exchange_rate);
        $this->assertEquals(self::$_main, $model->main);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'currency'=>self::$_currency, 'exchange_rate'=>self::$_exchange_rate, 'main'=>self::$_main];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_currency, $model->currency);
        $this->assertEquals(self::$_exchange_rate, $model->exchange_rate);
        $this->assertEquals(self::$_main, $model->main);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id];
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_SET_CURRENCY]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_SET_CURRENCY]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_ADD]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('currency', $model->errors));
        $this->assertTrue(array_key_exists('exchange_rate', $model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_ADD]);
        $model->attributes = ['currency'=>self::$_currency, 'exchange_rate'=>self::$_exchange_rate];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('currency', $model->errors));
        $this->assertEquals(self::$_message, $model->errors['currency'][0]);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_ADD]);
        $model->attributes = ['currency'=>self::$_currencyFresh, 'exchange_rate'=>self::$_exchange_rate];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_UPDATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(3, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        $this->assertTrue(array_key_exists('currency', $model->errors));
        $this->assertTrue(array_key_exists('exchange_rate', $model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'currency'=>self::$_currency2, 'exchange_rate'=>self::$_exchange_rate, 'main'=>self::$_main];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('currency', $model->errors));
        $this->assertEquals(self::$_message, $model->errors['currency'][0]);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_UPDATE]);
        $model->attributes = ['id'=>self::$_id, 'currency'=>self::$_currencyFresh, 'exchange_rate'=>self::$_exchange_rate, 'main'=>self::$_main];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_DELETE]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод CurrencyModel::getDataArray
     */
    public function testGetData()
    {
        $model = new CurrencyModel();
        $model->id = self::$_id;
        $model->currency = self::$_currency;
        $model->exchange_rate = self::$_exchange_rate;
        $model->main = self::$_main;
        
        $result = $model->getDataArray();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_currency, $result['currency']);
        $this->assertEquals(self::$_exchange_rate, $result['exchange_rate']);
        $this->assertEquals(self::$_main, $result['main']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
