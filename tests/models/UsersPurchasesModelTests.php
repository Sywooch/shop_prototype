<?php

namespace app\test\models;

use app\models\UsersPurchasesModel;

/**
 * Тестирует UsersPurchasesModel
 */
class UsersPurchasesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_id_users = 2;
    private static $_id_products = 3;
    private static $_id_deliveries = 4;
    private static $_id_payments = 5;
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\UsersPurchasesModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new UsersPurchasesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'id_users'));
        $this->assertTrue(property_exists($model, 'id_products'));
        $this->assertTrue(property_exists($model, 'id_deliveries'));
        $this->assertTrue(property_exists($model, 'id_payments'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_deliveries));
        $this->assertFalse(empty($model->id_payments));
        
        $this->assertEquals(self::$_id_users, $model->id_users);
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_deliveries, $model->id_deliveries);
        $this->assertEquals(self::$_id_payments, $model->id_payments);
        
        $model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_deliveries));
        $this->assertFalse(empty($model->id_payments));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_id_users, $model->id_users);
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_deliveries, $model->id_deliveries);
        $this->assertEquals(self::$_id_payments, $model->id_payments);
    }
}
