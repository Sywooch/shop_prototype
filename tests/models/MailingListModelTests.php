<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\MailingListModel;

/**
 * Тестирует класс app\models\MailingListModel
 */
class MailingListModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'mailing_list'=>'app\tests\source\fixtures\MailingListFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\MailingListModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\MailingListModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_REGISTRATION'));
        
        $model = new MailingListModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('description', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->mailing_list['mailing_list_1'];
        
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'name'=>$fixture['name'],
            'description'=>$fixture['description'],
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['description'], $model->description);
        
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'name'=>$fixture['name'],
            'description'=>$fixture['description'],
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['description'], $model->description);
        
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_REGISTRATION]);
        $model->attributes = [
            'id'=>$fixture['id'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $mailingListQuery = MailingListModel::find();
        $mailingListQuery->extendSelect(['id', 'name', 'description']);
        
        $queryRaw = clone $mailingListQuery;
        
        $expectedQuery = "SELECT `mailing_list`.`id`, `mailing_list`.`name`, `mailing_list`.`description` FROM `mailing_list`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $mailingListQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof MailingListModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->mailing_list['mailing_list_1'];
        
        $mailingListQuery = MailingListModel::find();
        $mailingListQuery->extendSelect(['id', 'name', 'description']);
        $mailingListQuery->where(['mailing_list.name'=>$fixture['name']]);
        
        $queryRaw = clone $mailingListQuery;
        
        $expectedQuery = sprintf("SELECT `mailing_list`.`id`, `mailing_list`.`name`, `mailing_list`.`description` FROM `mailing_list` WHERE `mailing_list`.`name`='%s'", $fixture['name']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $mailingListQuery->one();
        
        $this->assertTrue($result instanceof MailingListModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
