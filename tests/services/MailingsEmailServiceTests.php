<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\MailingsEmailService;
use app\tests\DbManager;
use app\tests\sources\fixtures\EmailsMailingsFixture;

/**
 * Тестирует класс MailingsEmailService
 */
class MailingsEmailServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод MailingsEmailService::handle
     * если пуст $request[email]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = [];
        
        $service = new MailingsEmailService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод MailingsEmailService::handle
     * если пуст $request[diffIdArray]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: diffIdArray
     */
    public function testHandleEmptyDiffIdArray()
    {
        $request = ['email'=>'some@some.com'];
        
        $service = new MailingsEmailService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод MailingsEmailService::handle
     */
    public function testHandle()
    {
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertEmpty($files);
        
        $request = ['email'=>'some@some.com', 'diffIdArray'=>[1, 2, 3, 4, 5]];
        
        $service = new MailingsEmailService();
        $service->handle($request);
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertNotEmpty($files);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        
        if (file_exists($saveDir) && is_dir($saveDir)) {
            $files = glob($saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
