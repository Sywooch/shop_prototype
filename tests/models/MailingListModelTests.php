<?php

namespace app\tests\models;

use app\tests\DbManager;
use app\models\MailingListModel;

/**
 * Тестирует класс app\models\MailingListModel
 */
class MailingListModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 2;
    private static $_idFromForm = [23,12,34];
    private static $_name = 'some name';
    private static $_description = 'some description';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('app\models\MailingListModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{mailing_list}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств и констант MailingListModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_MAILING_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_MAILING_FORM_REQUIRE'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('name'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('description'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_allMailingList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('idFromForm'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'description'=>self::$_description];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM]);
        $model->attributes = ['idFromForm'=>self::$_idFromForm];
        
        $this->assertFalse(empty($model->idFromForm));
        
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
        $model->attributes = ['idFromForm'=>self::$_idFromForm];
        
        $this->assertFalse(empty($model->idFromForm));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('idFromForm', $model->errors));
        
        $model = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
        $model->attributes = ['idFromForm'=>self::$_idFromForm];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод MailingListModel::getAllMailingList
     */
    public function testGetAllMailingList()
    {
        $model = new MailingListModel();
        
        $this->assertTrue(is_array($model->allMailingList));
        $this->assertFalse(empty($model->allMailingList));
        $this->assertTrue(is_object($model->allMailingList[0]));
        $this->assertTrue($model->allMailingList[0] instanceof MailingListModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
