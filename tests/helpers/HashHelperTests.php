<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\HashHelper;
use app\models\EmailsModel;
use app\tests\DbManager;

/**
 * Тестирует класс app\helpers\HashHelper
 */
class HashHelperTests extends TestCase
{
    private static $_dbClass;
    private static $_elm1 = 'some@some.com';
    private static $_elm2 = 56;
    private static $_elm3 = 14;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>'app\tests\sources\fixtures\EmailsFixture',
                'users'=>'app\tests\sources\fixtures\UsersFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод HashHelper::createHash
     */
    public function testCreateHash()
    {
        $hash = HashHelper::createHash([self::$_elm1, self::$_elm2, self::$_elm2]);
        
        $this->assertEquals(40, strlen($hash));
        
        $expectedHash = HashHelper::createHash([self::$_elm1, self::$_elm2, self::$_elm2]);
        
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createHashRestore
     */
    public function testCreateHashRestore()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $emailsModel = new EmailsModel($fixture);
        
        $hash = HashHelper::createHashRestore($emailsModel);
        
        $this->assertEquals(40, strlen($hash));
        
        $expectedHash = HashHelper::createHash([$emailsModel->email, $emailsModel->id, $emailsModel->user->id, \Yii::$app->session->getFlash('restore.' . $fixture['email'])]);
        
        $this->assertEquals($expectedHash, $hash);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
