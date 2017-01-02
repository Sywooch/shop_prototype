<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\RegistrationEmailService;

/**
 * Тестирует класс RegistrationEmailService
 */
class RegistrationEmailServiceTests extends TestCase
{
    /**
     * Тестирует свойства RegistrationEmailService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RegistrationEmailService::class);
        
        $this->assertTrue($reflection->hasProperty('emailRegistrationArray'));
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод RegistrationEmailService::getEmailRegistrationArray
     */
    public function testGetEmailRegistrationArray()
    {
        $service = new RegistrationEmailService();
        
        $reflection = new \ReflectionProperty($service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 'some@some.com');
        
        $reflection = new \ReflectionMethod($service, 'getEmailRegistrationArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод RegistrationEmailService::handle
     * если пуст email
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: email
     */
    public function testHandleEmptyEmail()
    {
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertEmpty($files);
        
        $request = [];
        
        $service = new RegistrationEmailService();
        $service->handle($request);
        
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод RegistrationEmailService::handle
     */
    public function testHandle()
    {
        $request = ['email'=>'some@some.com'];
        
        $service = new RegistrationEmailService();
        $service->handle($request);
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
