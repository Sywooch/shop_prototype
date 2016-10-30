<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\PaymentsModel;

/**
 * Тестирует класс app\models\PaymentsModel
 */
class PaymentsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>'app\tests\sources\fixtures\PaymentsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\PaymentsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\PaymentsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new PaymentsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('description', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->payments['payment_1'];
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_ORDER]);
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
        $fixture = self::$_dbClass->payments['payment_1'];
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_ORDER]);
        $model->attributes = [
            'id'=>$fixture['id'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $paymentsQuery = PaymentsModel::find();
        $paymentsQuery->extendSelect(['id', 'name', 'description']);
        
        $queryRaw = clone $paymentsQuery;
        
        $expectedQuery = "SELECT `payments`.`id`, `payments`.`name`, `payments`.`description` FROM `payments`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $paymentsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof PaymentsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->payments['payment_1'];
        
        $paymentsQuery = PaymentsModel::find();
        $paymentsQuery->extendSelect(['id', 'name', 'description']);
        $paymentsQuery->where(['[[payments.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $paymentsQuery;
        
        $expectedQuery = sprintf("SELECT `payments`.`id`, `payments`.`name`, `payments`.`description` FROM `payments` WHERE `payments`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $paymentsQuery->one();
        
        $this->assertTrue($result instanceof PaymentsModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->payments['payment_1'];
        $fixture2 = self::$_dbClass->payments['payment_2'];
        
        $paymentsQuery = PaymentsModel::find();
        $paymentsQuery->extendSelect(['id', 'name']);
        $paymentsArray = $paymentsQuery->allMap('id', 'name');
        
        $this->assertFalse(empty($paymentsArray));
        $this->assertTrue(array_key_exists($fixture['id'], $paymentsArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $paymentsArray));
        $this->assertTrue(in_array($fixture['name'], $paymentsArray));
        $this->assertTrue(in_array($fixture2['name'], $paymentsArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
