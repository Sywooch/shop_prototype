<?php

namespace app\tests\models;

use app\tests\DbManager;
use app\models\CurrencyModel;

/**
 * Тестирует класс app\models\CurrencyModel
 */
class CurrencyModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_exchange_rate = '132.4567';
    private static $_main = true;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\CurrencyModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_main]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\CurrencyModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
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
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'currency'=>self::$_currency, 'exchange_rate'=>self::$_exchange_rate, 'main'=>self::$_main];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_currency, $model->currency);
        $this->assertEquals(self::$_exchange_rate, $model->exchange_rate);
        $this->assertEquals(self::$_main, $model->main);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'currency'=>self::$_currency, 'exchange_rate'=>self::$_exchange_rate, 'main'=>self::$_main];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_currency, $model->currency);
        $this->assertEquals(self::$_exchange_rate, $model->exchange_rate);
        $this->assertEquals(self::$_main, $model->main);
    }
}
