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
                'address'=>'app\tests\source\fixtures\AddressFixture',
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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new AddressModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('address', $model->attributes));
        $this->assertTrue(array_key_exists('city', $model->attributes));
        $this->assertTrue(array_key_exists('country', $model->attributes));
        $this->assertTrue(array_key_exists('postcode', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->address['address_1'];
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'address'=>$fixture['address'], 
            'city'=>$fixture['city'], 
            'country'=>$fixture['country'], 
            'postcode'=>$fixture['postcode'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['address'], $model->address);
        $this->assertEquals($fixture['city'], $model->city);
        $this->assertEquals($fixture['country'], $model->country);
        $this->assertEquals($fixture['postcode'], $model->postcode);
        
        $model = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'address'=>$fixture['address'], 
            'city'=>$fixture['city'], 
            'country'=>$fixture['country'], 
            'postcode'=>$fixture['postcode'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['address'], $model->address);
        $this->assertEquals($fixture['city'], $model->city);
        $this->assertEquals($fixture['country'], $model->country);
        $this->assertEquals($fixture['postcode'], $model->postcode);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
