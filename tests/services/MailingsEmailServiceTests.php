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
    private $service;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->service = new MailingsEmailService();
    }
    
    /**
     * Тестирует метод MailingsEmailService::setEmail
     */
    public function testSetEmaill()
    {
        $this->service->setEmail('mail@mail.com');
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals('mail@mail.com', $result);
    }
    
    /**
     * Тестирует метод MailingsEmailService::setMailingsArray
     */
    public function testSetMailingsArray()
    {
        $this->service->setMailingsArray([new class() {}]);
        
        $reflection = new \ReflectionProperty($this->service, 'mailingsArray');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод MailingsEmailService::get
     * если пуст MailingsEmailService::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testGetEmptyEmail()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод MailingsEmailService::get
     * если пуст MailingsEmailService::mailingsArray
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailingsArray
     */
    public function testGetEmptyIdArray()
    {
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, 'mail@mail.com');
        
        $this->service->get();
    }
    
    /**
     * Тестирует метод MailingsEmailService::get
     */
    public function testGet()
    {
        $mailingsArray = [
            new class() {
                public $id = 1;
                public $name = 'Name 1';
                public $description = 'Descriptio 1';
            },
            new class() {
                public $id = 2;
                public $name = 'Name 2';
                public $description = 'Descriptio 2';
            },
        ];
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        $this->assertEmpty($files);
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, 'mail@mail.com');
        
        $reflection = new \ReflectionProperty($this->service, 'mailingsArray');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $mailingsArray);
        
        $this->service->get();
        
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
