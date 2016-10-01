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
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
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
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
