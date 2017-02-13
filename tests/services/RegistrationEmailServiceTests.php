<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\RegistrationEmailService;

/**
 * Тестирует класс RegistrationEmailService
 */
class RegistrationEmailServiceTests extends TestCase
{
    private $service;
    
    public function setUp()
    {
        $this->service = new RegistrationEmailService();
    }
    
    /**
     * Тестирует свойства RegistrationEmailService
     */
    public function testProperies()
    {
        $reflection = new \ReflectionClass(RegistrationEmailService::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод RegistrationEmailService::setEmail
     * переметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $this->service->setEmail([]);
    }
    
    /**
     * Тестирует метод RegistrationEmailService::setEmail
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
     * Тестирует метод RegistrationEmailService::get
     * если пуст RegistrationEmailService::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testGetEmptyEmail()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод RegistrationEmailService::get
     */
    public function testGet()
    {
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertEmpty($files);
        
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
