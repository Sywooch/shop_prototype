<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\PostcodesModel;

/**
 * Тестирует класс app\models\PostcodesModel
 */
class PostcodesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'postcodes'=>'app\tests\sources\fixtures\PostcodesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\PostcodesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\PostcodesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new PostcodesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('postcode', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->postcodes['postcode_1'];
        
        $model = new PostcodesModel(['scenario'=>PostcodesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'postcode'=>$fixture['postcode'], 
        ];
        
        $this->assertEquals($fixture['postcode'], $model->postcode);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->postcodes['postcode_2'];
        
        $model = new PostcodesModel(['scenario'=>PostcodesModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('postcode', $model->errors));
        
        $model = new PostcodesModel(['scenario'=>PostcodesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'postcode'=>$fixture['postcode'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $postcodesQuery = PostcodesModel::find();
        $postcodesQuery->extendSelect(['id', 'postcode']);
        
        $queryRaw = clone $postcodesQuery;
        
        $expectedQuery = "SELECT `postcodes`.`id`, `postcodes`.`postcode` FROM `postcodes`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $postcodesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof PostcodesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->postcodes['postcode_1'];
        
        $postcodesQuery = PostcodesModel::find();
        $postcodesQuery->extendSelect(['id', 'postcode']);
        $postcodesQuery->where(['[[postcodes.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $postcodesQuery;
        
        $expectedQuery = sprintf("SELECT `postcodes`.`id`, `postcodes`.`postcode` FROM `postcodes` WHERE `postcodes`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $postcodesQuery->one();
        
        $this->assertTrue($result instanceof PostcodesModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->postcodes['postcode_1'];
        $fixture2 = self::$_dbClass->postcodes['postcode_2'];
        
        $postcodesQuery = PostcodesModel::find();
        $postcodesQuery->extendSelect(['id', 'postcode']);
        $postcodesArray = $postcodesQuery->allMap('id', 'postcode');
        
        $this->assertFalse(empty($postcodesArray));
        $this->assertTrue(array_key_exists($fixture['id'], $postcodesArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $postcodesArray));
        $this->assertTrue(in_array($fixture['postcode'], $postcodesArray));
        $this->assertTrue(in_array($fixture2['postcode'], $postcodesArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
