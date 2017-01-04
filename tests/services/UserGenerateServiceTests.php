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
     * Тестирует свойства UserGenerateService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserGenerateService::class);
        
        $this->assertTrue($reflection->hasProperty('passwordGenerateSuccessArray'));
        $this->assertTrue($reflection->hasProperty('passwordGenerateEmptyArray'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('tempPassword'));
    }
    
    /**
     * Тестирует метод UserGenerateService::getPasswordGenerateEmptyArray
     */
    public function testGetPasswordGenerateEmptyArray()
    {
        $service = new UserGenerateService();
        
        $reflection = new \ReflectionMethod($service, 'getPasswordGenerateEmptyArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод UserGenerateService::getPasswordGenerateSuccessArray
     */
    public function testGetPasswordGenerateSuccessArray()
    {
        $service = new UserGenerateService();
        
        $reflection = new \ReflectionProperty($service, 'tempPassword');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 'tempPassword');
        
        $reflection = new \ReflectionMethod($service, 'getPasswordGenerateSuccessArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('tempPassword', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['tempPassword']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод UserGenerateService::handle
     * если пуст $request
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyGet()
    {
        $request = new class() {
            private $items = [];
            public function get($key)
            {
                if (array_key_exists($key, $this->items)) {
                    return $this->items[$key];
                }
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
        $request = new class() {
            private $items = [
                'email'=>'some@some.com'
            ];
            public function get($key)
            {
                if (array_key_exists($key, $this->items)) {
                    return $this->items[$key];
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
        $request = new class() {
            private $items = [
                'recovery'=>'recoveryKey'
            ];
            public function get($key)
            {
                if (array_key_exists($key, $this->items)) {
                    return $this->items[$key];
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
        
        $request = new class() {
            private $items = [
                'email'=>'email@some.com',
                'recovery'=>'recoveryKey'
            ];
            public function get($key)
            {
                if (array_key_exists($key, $this->items)) {
                    return $this->items[$key];
                }
            }
        };
        
        $service = new UserGenerateService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('emptyConfig', $result);
        
        $this->assertArrayNotHasKey('successConfig', $result);

        $this->assertInternalType('array', $result['userConfig']);
        $this->assertInternalType('array', $result['cartConfig']);
        $this->assertInternalType('array', $result['currencyConfig']);
        $this->assertInternalType('array', $result['searchConfig']);
        $this->assertInternalType('array', $result['menuConfig']);
        $this->assertInternalType('array', $result['emptyConfig']);
        
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
        
        $request = new class() {
            public $email;
            public $key;
            public function get($key)
            {
                return $key === 'email' ? $this->email : $this->key;
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
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('successConfig', $result);
        
        $this->assertArrayNotHasKey('emptyConfig', $result);

        $this->assertInternalType('array', $result['userConfig']);
        $this->assertInternalType('array', $result['cartConfig']);
        $this->assertInternalType('array', $result['currencyConfig']);
        $this->assertInternalType('array', $result['searchConfig']);
        $this->assertInternalType('array', $result['menuConfig']);
        $this->assertInternalType('array', $result['successConfig']);
        
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
