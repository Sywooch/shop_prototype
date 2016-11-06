<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\AddressModel;

/**
 * Тестирует класс app\models\AddressModel
 */
class AddressModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'address'=>'app\tests\sources\fixtures\AddressFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\AddressModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\AddressModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new AddressModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('address', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->address['address_1'];
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_ORDER]);
        $model->attributes = [
            'address'=>$fixture['address'], 
        ];
        
        $this->assertEquals($fixture['address'], $model->address);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->address['address_2'];
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('address', $model->errors));
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_ORDER]);
        $model->attributes = [
            'address'=>$fixture['address'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $addressQuery = AddressModel::find();
        $addressQuery->extendSelect(['id', 'address']);
        
        $queryRaw = clone $addressQuery;
        
        $expectedQuery = "SELECT `address`.`id`, `address`.`address` FROM `address`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $addressQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof AddressModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->address['address_1'];
        
        $addressQuery = AddressModel::find();
        $addressQuery->extendSelect(['id', 'address']);
        $addressQuery->where(['[[address.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $addressQuery;
        
        $expectedQuery = sprintf("SELECT `address`.`id`, `address`.`address` FROM `address` WHERE `address`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $addressQuery->one();
        
        $this->assertTrue($result instanceof AddressModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
