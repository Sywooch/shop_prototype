<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{EmailsMailingListModel,
    EmailsModel,
    MailingListModel};

/**
 * Тестирует класс app\models\EmailsMailingListModel
 */
class EmailsMailingListModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailing_list'=>'app\tests\source\fixtures\EmailsMailingListFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\EmailsMailingListModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\EmailsMailingListModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $model = new EmailsMailingListModel();
        
        $this->assertTrue(array_key_exists('id_email', $model->attributes));
        $this->assertTrue(array_key_exists('id_mailing_list', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->emails_mailing_list['emails_mailing_list_1'];
        
        $model = new EmailsMailingListModel(['scenario'=>EmailsMailingListModel::GET_FROM_DB]);
        $model->attributes = [
            'id_email'=>$fixture['id_email'], 
            'id_mailing_list'=>$fixture['id_mailing_list'],
        ];
        
        $this->assertEquals($fixture['id_email'], $model->id_email);
        $this->assertEquals($fixture['id_mailing_list'], $model->id_mailing_list);
        
        $model = new EmailsMailingListModel(['scenario'=>EmailsMailingListModel::GET_FROM_FORM]);
        $model->attributes = [
            'id_email'=>$fixture['id_email'], 
            'id_mailing_list'=>$fixture['id_mailing_list'],
        ];
        
        $this->assertEquals($fixture['id_email'], $model->id_email);
        $this->assertEquals($fixture['id_mailing_list'], $model->id_mailing_list);
    }
    
    /**
     * Тестирует метод EmailsMailingListModel::batchInsert
     */
    public function testBatchInsert()
    {
        $fixture_1 = self::$_dbClass->emails_mailing_list['emails_mailing_list_1'];
        $fixture_2 = self::$_dbClass->emails_mailing_list['emails_mailing_list_2'];
        
        $emailsModel = new EmailsModel(['id'=>$fixture_1['id_email']]);
        $mailingListModel = new MailingListModel(['id'=>[$fixture_1['id_mailing_list'], $fixture_2['id_mailing_list']]]);
        
        \Yii::$app->db->createCommand('DELETE FROM [[emails_mailing_list]]')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM [[emails_mailing_list]]')->queryAll()));
        
        $result = EmailsMailingListModel::batchInsert($mailingListModel, $emailsModel);
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertFalse(empty($result = \Yii::$app->db->createCommand('SELECT * FROM [[emails_mailing_list]]')->queryAll()));
        $this->assertEquals(2, count($result));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $emailsMailingListQuery = EmailsMailingListModel::find();
        $emailsMailingListQuery->extendSelect(['id_mailing_list', 'id_email']);
        
        $queryRaw = clone $emailsMailingListQuery;
        
        $expectedQuery = "SELECT `emails_mailing_list`.`id_mailing_list`, `emails_mailing_list`.`id_email` FROM `emails_mailing_list`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $emailsMailingListQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof EmailsMailingListModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->emails_mailing_list['emails_mailing_list_1'];
        
        $emailsMailingListQuery = EmailsMailingListModel::find();
        $emailsMailingListQuery->extendSelect(['id_mailing_list', 'id_email']);
        $emailsMailingListQuery->where(['emails_mailing_list.id_email'=>$fixture['id_email']]);
        
        $queryRaw = clone $emailsMailingListQuery;
        
        $expectedQuery = sprintf("SELECT `emails_mailing_list`.`id_mailing_list`, `emails_mailing_list`.`id_email` FROM `emails_mailing_list` WHERE `emails_mailing_list`.`id_email`=%d", $fixture['id_email']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $emailsMailingListQuery->one();
        
        $this->assertTrue($result instanceof EmailsMailingListModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
