<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\MailingsUnsubscribePostService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture};
use app\helpers\HashHelper;

/**
 * Тестирует класс MailingsUnsubscribePostService
 */
class MailingsUnsubscribePostServiceTests extends TestCase
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
     * Тестирует метод MailingsUnsubscribePostService::handle
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
                        'id'=>[1],
                        'email'=>null,
                        'key'=>'key',
                    ]
                ];
            }
        };
        
        $service = new MailingsUnsubscribePostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('mailingform-email', $result);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribePostService::handle
     * если ключи не совпали
     */
    public function testHandleNotSameKeys()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'MailingForm'=>[
                        'id'=>[1],
                        'email'=>'some@some.com',
                        'key'=>'key',
                    ]
                ];
            }
        };
        
        $service = new MailingsUnsubscribePostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribePostService::handle
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
                    'MailingForm'=>[
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
        
        $service = new MailingsUnsubscribePostService();
        $result = $service->handle($request);
        
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
