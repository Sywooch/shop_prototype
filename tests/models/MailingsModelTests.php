<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\MailingsModel;

/**
 * Тестирует класс app\models\MailingsModel
 */
class MailingsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>'app\tests\sources\fixtures\MailingsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\MailingsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\MailingsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_REGISTRATION'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_SUBSCRIBER'));
        
        $model = new MailingsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('description', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->mailings['mailings_1'];
        
        $model = new MailingsModel(['scenario'=>MailingsModel::GET_FROM_REGISTRATION]);
        $model->attributes = [
            'id'=>$fixture['id'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        
        $model = new MailingsModel(['scenario'=>MailingsModel::GET_FROM_ADD_SUBSCRIBER]);
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
        $model = new MailingsModel(['scenario'=>MailingsModel::GET_FROM_ADD_SUBSCRIBER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new MailingsModel(['scenario'=>MailingsModel::GET_FROM_ADD_SUBSCRIBER]);
        $model->attributes = [
            'id'=>1, 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $mailingsListQuery = MailingsModel::find();
        $mailingsListQuery->extendSelect(['id', 'name', 'description']);
        
        $queryRaw = clone $mailingsListQuery;
        
        $expectedQuery = "SELECT `mailings`.`id`, `mailings`.`name`, `mailings`.`description` FROM `mailings`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $mailingsListQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof MailingsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->mailings['mailing_1'];
        
        $mailingsListQuery = MailingsModel::find();
        $mailingsListQuery->extendSelect(['id', 'name', 'description']);
        $mailingsListQuery->where(['[[mailings.name]]'=>$fixture['name']]);
        
        $queryRaw = clone $mailingsListQuery;
        
        $expectedQuery = sprintf("SELECT `mailings`.`id`, `mailings`.`name`, `mailings`.`description` FROM `mailings` WHERE `mailings`.`name`='%s'", $fixture['name']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $mailingsListQuery->one();
        
        $this->assertTrue($result instanceof MailingsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
