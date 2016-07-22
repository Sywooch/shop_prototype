<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\AddressModel;

/**
 * Тестирует AddressModel
 */
class AddressModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_address = 'Some address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = 06589;
    private static $_postcode2 = 'F7895';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\AddressModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} SET [[id]]=:id, [[address]]=:address, [[city]]=:city, [[country]]=:country, [[postcode]]=:postcode');
        $command->bindValues([':id'=>self::$_id, ':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country, ':postcode'=>self::$_postcode]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new AddressModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, '_id'));
        $this->assertTrue(property_exists($model, 'address'));
        $this->assertTrue(property_exists($model, 'city'));
        $this->assertTrue(property_exists($model, 'country'));
        $this->assertTrue(property_exists($model, 'postcode'));
        
        $this->assertTrue(method_exists($model, 'getId'));
        $this->assertTrue(method_exists($model, 'setId'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id + 1, 'address'=>self::$_address, 'city'=>self::$_city, 'country'=>self::$_country, 'postcode'=>self::$_postcode2];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->address));
        $this->assertFalse(empty($model->city));
        $this->assertFalse(empty($model->country));
        $this->assertFalse(empty($model->postcode));
        
        $this->assertNotEquals(self::$_id + 1, $model->id);
        $this->assertEquals(self::$_address, $model->address);
        $this->assertEquals(self::$_city, $model->city);
        $this->assertEquals(self::$_country, $model->country);
        $this->assertEquals(self::$_postcode2, $model->postcode);
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id + 9, 'address'=>self::$_address, 'city'=>self::$_city, 'country'=>self::$_country, 'postcode'=>self::$_postcode2];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->address));
        $this->assertFalse(empty($model->city));
        $this->assertFalse(empty($model->country));
        $this->assertFalse(empty($model->postcode));
        
        $this->assertEquals(self::$_id + 9, $model->id);
        $this->assertEquals(self::$_address, $model->address);
        $this->assertEquals(self::$_city, $model->city);
        $this->assertEquals(self::$_country, $model->country);
        $this->assertEquals(self::$_postcode2, $model->postcode);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(3, count($model->errors));
        $this->assertTrue(array_key_exists('address', $model->errors));
        $this->assertTrue(array_key_exists('city', $model->errors));
        $this->assertTrue(array_key_exists('country', $model->errors));
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
        $model->attributes = ['address'=>self::$_address];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('city', $model->errors));
        $this->assertTrue(array_key_exists('country', $model->errors));
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
        $model->attributes = ['address'=>self::$_address, 'city'=>self::$_city, 'country'=>self::$_country];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод AddressModel::getId
     */
    public function testGetId()
    {
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
        $model->attributes = ['address'=>self::$_address, 'city'=>self::$_city, 'country'=>self::$_country, 'postcode'=>self::$_postcode];
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует метод AddressModel::getId
     * при условии отсутствия postcode
     */
    public function testGetIdWithoutPostcode()
    {
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
        $model->attributes = ['address'=>self::$_address, 'city'=>self::$_city, 'country'=>self::$_country];
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует возврат null в методе AddressModel::getId
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetId()
    {
        $model = new AddressModel();
        
        $this->assertTrue(is_null($model->id));
    }
    
    /**
     * Тестирует метод AddressModel::setId
     */
    public function testSetId()
    {
        $model = new AddressModel();
        $model->id = self::$_id + 3;
        
        $this->assertEquals(self::$_id + 3, $model->id);
    }
    
    /**
     * Тестирует метод AddressModel::getDataArray
     */
    public function testGetData()
    {
        $model = new AddressModel();
        $model->address = self::$_address;
        $model->city = self::$_city;
        $model->country = self::$_country;
        $model->postcode = self::$_postcode;
        
        $array = $model->getDataArray();
        
        $this->assertTrue(is_array($array));
        $this->assertTrue(array_key_exists('address', $array));
        $this->assertTrue(array_key_exists('city', $array));
        $this->assertTrue(array_key_exists('country', $array));
        $this->assertTrue(array_key_exists('postcode', $array));
        
        $this->assertEquals(self::$_address, $array['address']);
        $this->assertEquals(self::$_city, $array['city']);
        $this->assertEquals(self::$_country, $array['country']);
        $this->assertEquals(self::$_postcode, $array['postcode']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
