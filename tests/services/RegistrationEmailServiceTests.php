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
     * Тестирует метод RegistrationEmailService::handle
     * если пуст RegistrationEmailService::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = [];
        
        $service = new RegistrationEmailService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод RegistrationEmailService::handle
     */
    public function testHandle()
    {
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertEmpty($files);
        
        $request = ['email'=>'some@some.com'];
        
        $service = new RegistrationEmailService();
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
