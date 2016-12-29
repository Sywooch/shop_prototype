<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserLoginService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    EmailsFixture,
    UsersFixture};
use app\forms\UserLoginForm;
use app\controllers\ProductsListController;
use yii\helpers\Url;

/**
 * Тестирует класс UserLoginService
 */
class UserLoginServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'emails'=>EmailsFixture::class,
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства UserLoginService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserLoginService::class);
        
        $this->assertTrue($reflection->hasProperty('userLoginArray'));
        $this->assertTrue($reflection->hasProperty('form'));
    }
    
    /**
     * Тестирует метод UserLoginService::getUserLoginArray
     */
    public function testGetUserLoginArray()
    {
        $form = new class() extends UserLoginForm {};
        
        $service = new UserLoginService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $form);
        
        $reflection = new \ReflectionMethod($service, 'getUserLoginArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(UserLoginForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод UserLoginService::handle
     * если GET
     */
    public function testHandleGet()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $_GET = [];
        $request = new class() {
            public $isPost = false;
        };
        $form = new class() extends UserLoginForm {};
        
        $service = new UserLoginService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $form);
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('formConfig', $result);
        
        $this->assertInternalType('array', $result['userConfig']);
        $this->assertInternalType('array', $result['cartConfig']);
        $this->assertInternalType('array', $result['currencyConfig']);
        $this->assertInternalType('array', $result['searchConfig']);
        $this->assertInternalType('array', $result['menuConfig']);
        $this->assertInternalType('array', $result['formConfig']);
    }
    
    /**
     * Тестирует метод UserLoginService::handle
     * если POST
     */
    public function testHandlePost()
    {
        \Yii::$app->registry->clean();
        
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $fixtureEmail = self::$dbClass->emails['email_1'];
        $fixtureUser = self::$dbClass->users['user_1'];
        
        \Yii::$app->db->createCommand('UPDATE {{users}} SET [[password]]=:password WHERE [[id]]=:id')->bindValues([':password'=>password_hash($fixtureUser['password'], PASSWORD_DEFAULT), ':id'=>$fixtureUser['id']])->execute();
        
        $request = new class() {
            public $isPost = true;
            public $email;
            public $password;
            public function post()
            {
                return [
                    'UserLoginForm'=>[
                        'email'=>$this->email,
                        'password'=>$this->password,
                    ],
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, $fixtureEmail['email']);
        $reflection = new \ReflectionProperty($request, 'password');
        $reflection->setValue($request, $fixtureUser['password']);
        
        $form = new class() extends UserLoginForm {};
        
        $service = new UserLoginService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $form);
        
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertSame(Url::to(['/products-list/index']), $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        \Yii::$app->registry->clean();
    }
}
