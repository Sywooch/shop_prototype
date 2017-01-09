<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserGenerateService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    EmailsFixture,
    UsersFixture};
use app\controllers\UserController;
use yii\web\Request;

/**
 * Тестирует класс UserGenerateService
 */
class UserGenerateServiceTests extends TestCase
{
    private static $dbClass;
    
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
    
    /**
     * Тестирует метод UserGenerateService::handle
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
        
        $service = new UserGenerateService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод UserGenerateService::handle
     * если пуст $request[recoveryKey]
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
        
        $service = new UserGenerateService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод UserGenerateService::handle
     * если пуст $request[emailKey]
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
        
        $service = new UserGenerateService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод UserGenerateService::handle
     * если recoveryModel->email !== emailKey
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
        
        $service = new UserGenerateService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('cartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('passwordGenerateEmptyWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('passwordGenerateSuccessWidgetConfig', $result);

        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['cartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['passwordGenerateEmptyWidgetConfig']);
        
        $session->removeFlash('flash_key');
        $session->close();
    }
    
    /**
     * Тестирует метод UserGenerateService::handle
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
        
        $service = new UserGenerateService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('cartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('passwordGenerateSuccessWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('passwordGenerateEmptyWidgetConfig', $result);

        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['cartWidgetConfig']);
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
