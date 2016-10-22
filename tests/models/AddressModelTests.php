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
        $model = new AddressModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('address', $model->attributes));
        $this->assertTrue(array_key_exists('city', $model->attributes));
        $this->assertTrue(array_key_exists('country', $model->attributes));
        $this->assertTrue(array_key_exists('postcode', $model->attributes));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $addressQuery = AddressModel::find();
        $addressQuery->extendSelect(['id', 'address', 'city', 'country', 'postcode']);
        
        $queryRaw = clone $addressQuery;
        
        $expectedQuery = "SELECT `address`.`id`, `address`.`address`, `address`.`city`, `address`.`country`, `address`.`postcode` FROM `address`";
        
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
        $addressQuery->extendSelect(['id', 'address', 'city', 'country', 'postcode']);
        $addressQuery->where(['address.id'=>(int) $fixture['id']]);
        
        $queryRaw = clone $addressQuery;
        
        $expectedQuery = sprintf("SELECT `address`.`id`, `address`.`address`, `address`.`city`, `address`.`country`, `address`.`postcode` FROM `address` WHERE `address`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $addressQuery->one();
        
        $this->assertTrue($result instanceof AddressModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->address['address_1'];
        $fixture2 = self::$_dbClass->address['address_2'];
        
        $addressQuery = AddressModel::find();
        $addressQuery->extendSelect(['id', 'city']);
        $addressArray = $addressQuery->allMap('id', 'city');
        
        $this->assertFalse(empty($addressArray));
        $this->assertTrue(array_key_exists($fixture['id'], $addressArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $addressArray));
        $this->assertTrue(in_array($fixture['city'], $addressArray));
        $this->assertTrue(in_array($fixture2['city'], $addressArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
