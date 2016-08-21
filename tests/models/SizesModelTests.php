<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\SizesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует SizesModel
 */
class SizesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_size = '46';
    private static $_size2 = '35';
    private static $_size3 = '51';
    private static $_idArray = [1, 24];
    private static $_message = 'Такой размер уже добавлен!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\SizesModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id + 1, ':size'=>self::$_size2]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new SizesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_UPDATE_FORM'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'size'));
        $this->assertTrue(property_exists($model, 'idArray'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'size'=>self::$_size];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_size, $model->size);
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['idArray'=>self::$_idArray];
        
        $this->assertEquals(self::$_idArray, $model->idArray);
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['size'=>self::$_size];
        
        $this->assertEquals(self::$_size, $model->size);
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'size'=>self::$_size];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_size, $model->size);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('idArray', $model->errors));
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['idArray'=>self::$_idArray];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('size', $model->errors));
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['size'=>self::$_size];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('size', $model->errors));
        $this->assertEquals(self::$_message, $model->errors['size'][0]);
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_FORM]);
        $model->attributes = ['size'=>self::$_size3];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'size'=>self::$_size2];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('size', $model->errors));
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_UPDATE_FORM]);
        $model->attributes = ['id'=>self::$_id, 'size'=>self::$_size3];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
