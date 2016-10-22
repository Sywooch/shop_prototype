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
                'brands'=>'app\tests\sources\fixtures\BrandsFixture',
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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT'));
        
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
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT]);
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
        $fixture = self::$_dbClass->brands['brand_1'];
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT]);
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
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->brands['brand_1'];
        $fixture2 = self::$_dbClass->brands['brand_2'];
        
        $brandsQuery = BrandsModel::find();
        $brandsQuery->extendSelect(['id', 'brand']);
        $brandsArray = $brandsQuery->allMap('id', 'brand');
        
        $this->assertFalse(empty($brandsArray));
        $this->assertTrue(array_key_exists($fixture['id'], $brandsArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $brandsArray));
        $this->assertTrue(in_array($fixture['brand'], $brandsArray));
        $this->assertTrue(in_array($fixture2['brand'], $brandsArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
