<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\RecoveryEmailService;

/**
 * Тестирует класс RecoveryEmailService
 */
class RecoveryEmailServiceTests extends TestCase
{
    private $service;
    
    public function setUp()
    {
        $this->service = new RecoveryEmailService();
    }
    
    /**
     * Тестирует свойства RecoveryEmailService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RecoveryEmailService::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод RecoveryEmailService::setKey
     * переметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $this->service->setKey([]);
    }
    
    /**
     * Тестирует метод RecoveryEmailService::setKey
     */
    public function testSetKey()
    {
        $this->service->setKey('key');
        
        $reflection = new \ReflectionProperty($this->service, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals('key', $result);
    }
    
    /**
     * Тестирует метод RecoveryEmailService::setEmail
     * переметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $this->service->setEmail([]);
    }
    
    /**
     * Тестирует метод RecoveryEmailService::setEmail
     */
    public function testSetEmail()
    {
        $this->service->setEmail('mail@email.com');
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals('mail@email.com', $result);
    }
    
    /**
     * Тестирует метод RecoveryEmailService::get
     * если пуст RecoveryEmailService::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testGetEmptyKey()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод RecoveryEmailService::get
     * если пуст RecoveryEmailService::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testGetEmptyEmail()
    {
        $reflection = new \ReflectionProperty($this->service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, 'key');
        
        $this->service->get();
    }
    
    /**
     * Тестирует метод RecoveryEmailService::get
     */
    public function testGet()
    {
        $key = sha1('some key');
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        $this->assertEmpty($files);
        
        $reflection = new \ReflectionProperty($this->service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, 'key');
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, 'some@some.com');
        
        $this->service->get();
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertNotEmpty($files);
    }
    
    public static function tearDownAfterClass()
    {
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        
        if (file_exists($saveDir) && is_dir($saveDir)) {
            $files = glob($saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
