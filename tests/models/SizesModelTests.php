<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
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
                'sizes'=>'app\tests\sources\fixtures\SizesFixture',
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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT'));
        
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
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT]);
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
        $fixture = self::$_dbClass->sizes['size_1'];
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT]);
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
        $sizesQuery = SizesModel::find();
        $sizesQuery->extendSelect(['id', 'size']);
        
        $queryRaw = clone $sizesQuery;
        
        $expectedQuery = "SELECT `sizes`.`id`, `sizes`.`size` FROM `sizes`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $sizesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof SizesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->sizes['size_1'];
        
        $sizesQuery = SizesModel::find();
        $sizesQuery->extendSelect(['id', 'size']);
        $sizesQuery->where(['sizes.size'=>$fixture['size']]);
        
        $queryRaw = clone $sizesQuery;
        
        $expectedQuery = sprintf("SELECT `sizes`.`id`, `sizes`.`size` FROM `sizes` WHERE `sizes`.`size`=%d", $fixture['size']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $sizesQuery->one();
        
        $this->assertTrue($result instanceof SizesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
