<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\MailingsSaveService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture};

/**
 * Тестирует класс MailingsSaveService
 */
class MailingsSaveServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'emails_mailings'=>EmailsMailingsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод MailingsSaveService::handle
     * если запрос AJAX с ошибками
     */
    public function testHandleAjaxErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'MailingForm'=>[
                        'id'=>null,
                        'email'=>'some@some.com',
                    ]
                ];
            }
        };
        
        $service = new MailingsSaveService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailingform-id', $result);
    }
    
    /**
     * Тестирует метод MailingsSaveService::handle
     */
    public function testHandleAjax()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{emails_mailings}}')->execute();
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        
        $this->assertEmpty($result);
        
        $request = new class() {
            public $isAjax = true;
            public $email;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'MailingForm'=>[
                        'id'=>[1, 2],
                        'email'=>$this->email,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $service = new MailingsSaveService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        
        $this->assertCount(2, $result);
        
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
