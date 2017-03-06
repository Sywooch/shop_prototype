<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\MailingsUnsubscribePostRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture};
use app\helpers\HashHelper;

/**
 * Тестирует класс MailingsUnsubscribePostRequestHandler
 */
class MailingsUnsubscribePostRequestHandlerTests extends TestCase
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
        $this->handler = new MailingsUnsubscribePostRequestHandler();
    }
    
    /**
     * Тестирует метод MailingsUnsubscribePostRequestHandler::unsubscribeSuccessWidgetConfig
     */
    public function testUnsubscribeSuccessWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'unsubscribeSuccessWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribePostRequestHandler::handle
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
                        'id'=>[1],
                        'email'=>null,
                        'key'=>'key',
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribePostRequestHandler::handle
     * если ключи не совпали
     */
    public function testHandleNotSameKeys()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserMailingForm'=>[
                        'id'=>[1],
                        'email'=>'some@some.com',
                        'key'=>'key',
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribePostRequestHandler::handle
     */
    public function testHandle()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(4, $result);
        
        $request = new class() {
            public $isAjax = true;
            public $email;
            public $key;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserMailingForm'=>[
                        'id'=>[1, 2],
                        'email'=>$this->email,
                        'key'=>$this->key,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        $reflection = new \ReflectionProperty($request, 'key');
        $reflection->setValue($request, HashHelper::createHash([self::$dbClass->emails['email_1']['email']]));
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(2, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
