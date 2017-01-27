<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUnsubscribeFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    MailingsFixture};
use app\forms\MailingForm;

/**
 * Тестирует класс GetUnsubscribeFormWidgetConfigService
 */
class GetUnsubscribeFormWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'mailings'=>MailingsFixture::class,
                'emails_mailings'=>EmailsMailingsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetUnsubscribeFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUnsubscribeFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('unsubscribeFormWidgetArray'));
    }
    
    /**
     * Тестирует метод GetUnsubscribeFormWidgetConfigService::handle
     * если пуст request[unsubscribeKey]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: unsubscribeKey
     */
    public function testHandleEmptyUnsubscribeKey()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new GetUnsubscribeFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetUnsubscribeFormWidgetConfigService::handle
     * если пуст request[email]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                if ($name == \Yii::$app->params['unsubscribeKey']) {
                    return 'unsubscribeKey';
                } else {
                    return null;
                }
            }
        };
        
        $service = new GetUnsubscribeFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetUnsubscribeFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $email;
            public function get($name = null, $defaultValue = null)
            {
                return $this->email;
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $service = new GetUnsubscribeFormWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(MailingForm::class, $result['form']);
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
