<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use yii\helpers\ArrayHelper;
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
                'emails_mailing_list'=>'app\tests\sources\fixtures\EmailsMailingListFixture',
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
        $model = new EmailsMailingListModel();
        
        $this->assertTrue(array_key_exists('id_email', $model->attributes));
        $this->assertTrue(array_key_exists('id_mailing_list', $model->attributes));
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
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->emails_mailing_list['emails_mailing_list_1'];
        $fixture2 = self::$_dbClass->emails_mailing_list['emails_mailing_list_2'];
        
        $productsQuery = EmailsMailingListModel::find();
        $productsQuery->extendSelect(['id_mailing_list', 'id_email']);
        $productsQuery->asArray();
        $productsArray = $productsQuery->all();
        $productsArray = ArrayHelper::map($productsArray, 'id_mailing_list', 'id_email');
        
        $this->assertFalse(empty($productsArray));
        $this->assertTrue(array_key_exists($fixture['id_mailing_list'], $productsArray));
        $this->assertTrue(array_key_exists($fixture2['id_mailing_list'], $productsArray));
        $this->assertTrue(in_array($fixture['id_email'], $productsArray));
        $this->assertTrue(in_array($fixture2['id_email'], $productsArray));
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
        
        \Yii::$app->db->createCommand('DELETE FROM {{emails_mailing_list}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryAll()));
        
        $result = EmailsMailingListModel::batchInsert($mailingListModel, $emailsModel);
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertFalse(empty($result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryAll()));
        $this->assertEquals(2, count($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
