<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use yii\helpers\ArrayHelper;
use app\tests\DbManager;
use app\models\{EmailsMailingsModel,
    EmailsModel,
    MailingsModel};

/**
 * Тестирует класс app\models\EmailsMailingsModel
 */
class EmailsMailingsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>'app\tests\sources\fixtures\EmailsMailingsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\EmailsMailingsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\EmailsMailingsModel
     */
    public function testProperties()
    {
        $model = new EmailsMailingsModel();
        
        $this->assertTrue(array_key_exists('id_email', $model->attributes));
        $this->assertTrue(array_key_exists('id_mailing', $model->attributes));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $emailsMailingListQuery = EmailsMailingsModel::find();
        $emailsMailingListQuery->extendSelect(['id_mailing', 'id_email']);
        
        $queryRaw = clone $emailsMailingListQuery;
        
        $expectedQuery = "SELECT `emails_mailings`.`id_mailing`, `emails_mailings`.`id_email` FROM `emails_mailings`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $emailsMailingListQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof EmailsMailingsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->emails_mailings['email_mailing_1'];
        
        $emailsMailingListQuery = EmailsMailingsModel::find();
        $emailsMailingListQuery->extendSelect(['id_mailing', 'id_email']);
        $emailsMailingListQuery->where(['emails_mailings.id_email'=>$fixture['id_email']]);
        
        $queryRaw = clone $emailsMailingListQuery;
        
        $expectedQuery = sprintf("SELECT `emails_mailings`.`id_mailing`, `emails_mailings`.`id_email` FROM `emails_mailings` WHERE `emails_mailings`.`id_email`=%d", $fixture['id_email']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $emailsMailingListQuery->one();
        
        $this->assertTrue($result instanceof EmailsMailingsModel);
    }
    
    /**
     * Тестирует метод EmailsMailingsModel::batchInsert
     */
    public function testBatchInsert()
    {
        $fixture_1 = self::$_dbClass->emails_mailings['email_mailing_1'];
        $fixture_2 = self::$_dbClass->emails_mailings['email_mailing_2'];
        
        $emailsModel = new EmailsModel(['id'=>$fixture_1['id_email']]);
        $mailingsModel = new MailingsModel(['id'=>[$fixture_1['id_mailing'], $fixture_2['id_mailing']]]);
        
        \Yii::$app->db->createCommand('DELETE FROM {{emails_mailings}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll()));
        
        $result = EmailsMailingsModel::batchInsert($emailsModel, $mailingsModel);
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertFalse(empty($result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll()));
        $this->assertEquals(2, count($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
