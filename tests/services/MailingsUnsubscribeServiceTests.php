<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\MailingsUnsubscribeService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    EmailsFixture,
    EmailsMailingsFixture,
    CurrencyFixture,
    MailingsFixture};
use app\helpers\HashHelper;
use app\controllers\MailingsController;

/**
 * Тестирует класс MailingsUnsubscribeService
 */
class MailingsUnsubscribeServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'currency'=>CurrencyFixture::class,
                'emails'=>EmailsFixture::class,
                'mailings'=>MailingsFixture::class,
                'emails_mailings'=>EmailsMailingsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeService::handle
     * если пуст $request[unsubscribeKey]
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyUnsubscribeKey()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $service = new MailingsUnsubscribeService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeService::handle
     * если пуст $request[email]
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyEmail()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                if ($name == \Yii::$app->params['unsubscribeKey']) {
                    return 'unsubscribeKey';
                } else {
                    return null;
                }
            }
        };
        
        $service = new MailingsUnsubscribeService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeService::handle
     * если ключи не совпадают
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleKeysNotEquals()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                if ($name == \Yii::$app->params['unsubscribeKey']) {
                    return 'unsubscribeKey';
                } else {
                    return 'some@some.com';
                }
            }
        };
        
        $service = new MailingsUnsubscribeService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeService::handle
     * если нет подписок, связанных с переданным email
     */
    public function testHandleNotMailings()
    {
        \Yii::$app->controller = new MailingsController('mailings', \Yii::$app);
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                if ($name == \Yii::$app->params['unsubscribeKey']) {
                    return HashHelper::createHash(['some@some.com']);
                } else {
                    return 'some@some.com';
                }
            }
        };
        
        $service = new MailingsUnsubscribeService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailingsUnsubscribeEmptyWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('unsubscribeFormWidgetConfig', $result);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new MailingsController('mailings', \Yii::$app);
        
        $request = new class() {
            public $email;
            public $unsubscribe;
            public function get($name=null, $defaultValue=null)
            {
                if ($name == 'unsubscribe') {
                    return $this->unsubscribe;
                } else {
                    return $this->email;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'unsubscribe');
        $reflection->setValue($request, HashHelper::createHash([self::$dbClass->emails['email_1']['email']]));
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $service = new MailingsUnsubscribeService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('unsubscribeFormWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('mailingsUnsubscribeEmptyWidgetConfig', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
