<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\DeliveriesModel;

/**
 * Тестирует класс app\models\DeliveriesModel
 */
class DeliveriesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>'app\tests\sources\fixtures\DeliveriesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\DeliveriesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\DeliveriesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new DeliveriesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('description', $model->attributes));
        $this->assertTrue(array_key_exists('price', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->deliveries['delivery_1'];
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_ORDER]);
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
        $fixture = self::$_dbClass->deliveries['delivery_1'];
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_ORDER]);
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
        $deliveriesQuery = DeliveriesModel::find();
        $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
        
        $queryRaw = clone $deliveriesQuery;
        
        $expectedQuery = "SELECT `deliveries`.`id`, `deliveries`.`name`, `deliveries`.`description`, `deliveries`.`price` FROM `deliveries`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $deliveriesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof DeliveriesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->deliveries['delivery_1'];
        
        $deliveriesQuery = DeliveriesModel::find();
        $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
        $deliveriesQuery->where(['[[deliveries.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $deliveriesQuery;
        
        $expectedQuery = sprintf("SELECT `deliveries`.`id`, `deliveries`.`name`, `deliveries`.`description`, `deliveries`.`price` FROM `deliveries` WHERE `deliveries`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $deliveriesQuery->one();
        
        $this->assertTrue($result instanceof DeliveriesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
