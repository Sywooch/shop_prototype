<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\MailingsSaveRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture};

/**
 * Тестирует класс MailingsSaveRequestHandler
 */
class MailingsSaveRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
    
    public function setUp()
    {
        $this->handler = new MailingsSaveRequestHandler();
    }
    
    /**
     * Тестирует метод MailingsSaveRequestHandler::mailingsSuccessWidgetConfig
     */
    public function testMailingsSuccessWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'mailingsSuccessWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод MailingsSaveRequestHandler::handle
     * если запрос AJAX с ошибками
     */
    public function testHandleAjaxErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserMailingForm'=>[
                        'id'=>null,
                        'email'=>'some@some.com',
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsSaveRequestHandler::handle
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
                    'UserMailingForm'=>[
                        'id'=>[1, 2],
                        'email'=>$this->email,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $result = $this->handler->handle($request);
        
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
