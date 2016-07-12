<?php

namespace app\test\models;

use app\models\CurrencyModel;

/**
 * Тестирует CurrencyModel
 */
class CurrencyModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.5698';
    private static $_main = '1';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\CurrencyModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new CurrencyModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_SET'));
        
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
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->currency));
        $this->assertFalse(empty($model->exchange_rate));
        $this->assertFalse(empty($model->main));
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_currency, $model->currency);
        $this->assertEquals(self::$_exchange_rate, $model->exchange_rate);
        $this->assertEquals(self::$_main, $model->main);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM_SET]);
        $model->attributes = ['id'=>self::$_id];
        
        $this->assertFalse(empty($model->id));
        $this->assertEquals(self::$_id, $model->id);
    }
}
