<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\PhonesFixture;
use app\models\PhonesModel;

/**
 * Тестирует класс app\models\PhonesModel
 */
class PhonesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'phones'=>PhonesFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\PhonesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\PhonesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new PhonesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('phone', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'phone'=>$fixture['phone'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['phone'], $model->phone);
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'phone'=>$fixture['phone'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['phone'], $model->phone);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
