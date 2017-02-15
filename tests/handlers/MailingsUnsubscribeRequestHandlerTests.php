<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\MailingsUnsubscribeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    EmailsFixture,
    EmailsMailingsFixture,
    CurrencyFixture,
    MailingsFixture};
use app\helpers\HashHelper;
use app\controllers\MailingsController;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс MailingsUnsubscribeRequestHandler
 */
class MailingsUnsubscribeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
        
        $this->handler = new MailingsUnsubscribeRequestHandler();
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeRequestHandler::unsubscribeFormWidgetConfig
     */
    public function testUnsubscribeFormWidgetConfig()
    {
        $mailingForm = new class() extends AbstractBaseForm {};
        $mailingsModelArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'unsubscribeFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingForm, $mailingsModelArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeRequestHandler::handle
     * если пуст MailingsUnsubscribeRequestHandler::unsubscribeKey
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
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeRequestHandler::handle
     * если пуст MailingsUnsubscribeRequestHandler::email
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
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeRequestHandler::handle
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
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeRequestHandler::handle
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
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('unsubscribeEmptyWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('unsubscribeFormWidgetConfig', $result);
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeRequestHandler::handle
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
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('unsubscribeFormWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('unsubscribeEmptyWidgetConfig', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
