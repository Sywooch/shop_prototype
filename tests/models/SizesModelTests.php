<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\SizesFixture;
use app\models\SizesModel;

/**
 * Тестирует класс app\models\SizesModel
 */
class SizesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\SizesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\SizesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new SizesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('size', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->sizes['size_1'];
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'size'=>$fixture['size'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['size'], $model->size);
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'size'=>$fixture['size'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['size'], $model->size);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
