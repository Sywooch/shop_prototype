<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\RecoveryEmailService;

/**
 * Тестирует класс RecoveryEmailService
 */
class RecoveryEmailServiceTests extends TestCase
{
    /**
     * Тестирует свойства RecoveryEmailService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RecoveryEmailService::class);
        
        $this->assertTrue($reflection->hasProperty('emailRecoveryArray'));
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод RecoveryEmailService::getEmailRecoveryArray
     */
    public function testGetEmailRecoveryArray()
    {
        $key = sha1('some key');
        
        $service = new RecoveryEmailService();
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $key);
        
        $reflection = new \ReflectionMethod($service, 'getEmailRecoveryArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('key', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['key']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод RecoveryEmailService::handle
     * если пуст RecoveryEmailService::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: key
     */
    public function testHandleEmptyKey()
    {
        $request = [];
        
        $service = new RecoveryEmailService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод RecoveryEmailService::handle
     * если пуст RecoveryEmailService::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: email
     */
    public function testHandleEmptyEmail()
    {
        $request = ['key'=>sha1('some key')];
        
        $service = new RecoveryEmailService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод RecoveryEmailService::handle
     */
    public function testHandle()
    {
        $key = sha1('some key');
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertEmpty($files);
        
        $request = ['key'=>$key, 'email'=>'some@some.com'];
        
        $service = new RecoveryEmailService();
        $service->handle($request);
        
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
