<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\SaveAddressFormModel;

/**
 * Тестирует класс app\models\SaveAddressFormModel
 */
class SaveAddressFormModelTests extends TestCase
{
    private static $_dbClass;
    public static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'address'=>'app\tests\sources\fixtures\AddressFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\SaveAddressFormModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\SaveAddressFormModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('SAVE'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('address'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->address['address_1'];
        
        $model = new SaveAddressFormModel(['scenario'=>SaveAddressFormModel::SAVE]);
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
        $fixture = self::$_dbClass->address['address_1'];
        
        $model = new SaveAddressFormModel(['scenario'=>SaveAddressFormModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('address', $model->errors));
        
        $model = new SaveAddressFormModel(['scenario'=>SaveAddressFormModel::SAVE]);
        $model->attributes = [
            'address'=>$fixture['address'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
