<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\BrandsModel;

/**
 * Тестирует класс app\models\BrandsModel
 */
class BrandsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>'app\tests\source\fixtures\BrandsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\BrandsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\BrandsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $model = new BrandsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('brand', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->brands['brand_1'];
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'brand'=>$fixture['brand'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['brand'], $model->brand);
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'brand'=>$fixture['brand'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['brand'], $model->brand);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $brandsQuery = BrandsModel::find();
        $brandsQuery->extendSelect(['id', 'brand']);
        
        $queryRaw = clone $brandsQuery;
        
        $expectedQuery = "SELECT `brands`.`id`, `brands`.`brand` FROM `brands`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $brandsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof BrandsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->brands['brand_1'];
        
        $brandsQuery = BrandsModel::find();
        $brandsQuery->extendSelect(['id', 'brand']);
        $brandsQuery->where(['brands.brand'=>$fixture['brand']]);
        
        $queryRaw = clone $brandsQuery;
        
        $expectedQuery = sprintf("SELECT `brands`.`id`, `brands`.`brand` FROM `brands` WHERE `brands`.`brand`='%s'", $fixture['brand']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $brandsQuery->one();
        
        $this->assertTrue($result instanceof BrandsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
