<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\UserGenerateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    EmailsFixture,
    UsersFixture};
use app\controllers\UserController;
use yii\web\Request;

/**
 * Тестирует класс UserGenerateRequestHandler
 */
class UserGenerateRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
                'emails'=>EmailsFixture::class,
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->handler = new UserGenerateRequestHandler();
    }
    
    /**
     * Тестирует метод UserGenerateRequestHandler::passwordGenerateEmptyWidgetConfig
     */
    public function testPasswordGenerateEmptyWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'passwordGenerateEmptyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод UserGenerateRequestHandler::passwordGenerateSuccessWidgetConfig
     */
    public function testPasswordGenerateSuccessWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'passwordGenerateSuccessWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, 'j9Ij0Oij&jcfL');
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('tempPassword', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['tempPassword']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод UserGenerateRequestHandler::handle
     * если пуст $request
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyGet()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод UserGenerateRequestHandler::handle
     * если пуст $key
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyRecoveryKey()
    {
        $request = new class() extends Request {
            private $items = [
                'email'=>'some@some.com'
            ];
            public function get($name = null, $defaultValue = null)
            {
                if (array_key_exists($name, $this->items)) {
                    return $this->items[$name];
                }
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод UserGenerateRequestHandler::handle
     * если пуст $email
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyEmailKey()
    {
        $request = new class() extends Request {
            private $items = [
                'recovery'=>'recoveryKey'
            ];
            public function get($name = null, $defaultValue = null)
            {
                if (array_key_exists($name, $this->items)) {
                    return $this->items[$name];
                }
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод UserGenerateRequestHandler::handle
     * если recoveryModel->email !== $email
     */
    public function testHandleNotEqualsEmail()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->setFlash('flash_key', ['email'=>'some@some.com']);
        
        $request = new class() extends Request {
            private $items = [
                'email'=>'email@some.com',
                'recovery'=>'recoveryKey'
            ];
            public function get($name = null, $defaultValue = null)
            {
                if (array_key_exists($name, $this->items)) {
                    return $this->items[$name];
                }
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('passwordGenerateEmptyWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('passwordGenerateSuccessWidgetConfig', $result);

        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['passwordGenerateEmptyWidgetConfig']);
        
        $session->removeFlash('flash_key');
        $session->close();
    }
    
    /**
     * Тестирует метод UserGenerateRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $emailFixture = self::$dbClass->emails['email_1'];
        $key = sha1('some string');
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} INNER JOIN {{emails}} ON [[users.id_email]]=[[emails.id]] WHERE [[emails.email]]=:email')->bindValue(':email', $emailFixture['email'])->queryOne();
        $oldPass = $result['password'];
        
        $session = \Yii::$app->session;
        $session->open();
        $session->setFlash($key, ['email'=>$emailFixture['email']]);
        
        $request = new class() extends Request {
            public $email;
            public $key;
            public function get($name = null, $defaultValue = null)
            {
                return $name === 'email' ? $this->email : $this->key;
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, $emailFixture['email']);
        $reflection = new \ReflectionProperty($request, 'key');
        $reflection->setValue($request, $key);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('passwordGenerateSuccessWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('passwordGenerateEmptyWidgetConfig', $result);

        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['passwordGenerateSuccessWidgetConfig']);
        
        $session->removeFlash($key);
        $session->close();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} INNER JOIN {{emails}} ON [[users.id_email]]=[[emails.id]] WHERE [[emails.email]]=:email')->bindValue(':email', $emailFixture['email'])->queryOne();
        $newPass = $result['password'];
        
        $this->assertNotEquals($oldPass, $newPass);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
