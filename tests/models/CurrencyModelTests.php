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
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_search = 'пиджак';
    
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
        
        $this->assertTrue(property_exists($model, 'categories'));
        $this->assertTrue(property_exists($model, 'subcategory'));
        $this->assertTrue(property_exists($model, 'search'));
        $this->assertTrue(property_exists($model, 'id_products'));
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
        $model->attributes = ['id'=>self::$_id, 'id_products'=>self::$_id, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode, 'search'=>self::$_search];
        
        $this->assertFalse(empty($model->id));
        $this->assertEquals(self::$_id, $model->id);
        
        $this->assertFalse(empty($model->id_products));
        $this->assertEquals(self::$_id, $model->id_products);
        
        $this->assertFalse(empty($model->categories));
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        
        $this->assertFalse(empty($model->subcategory));
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        
        $this->assertFalse(empty($model->search));
        $this->assertEquals(self::$_search, $model->search);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM_SET]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM_SET]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод CurrencyModel::getDataForSession
     */
    public function testGetDataForSession()
    {
        $model = new CurrencyModel();
        $model->id = self::$_id;
        $model->currency = self::$_currency;
        $model->exchange_rate = self::$_exchange_rate;
        $model->main = self::$_main;
        
        $result = $model->getDataForSession();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_currency, $result['currency']);
        $this->assertEquals(self::$_exchange_rate, $result['exchange_rate']);
        $this->assertEquals(self::$_main, $result['main']);
    }
}
