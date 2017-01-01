<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserRegistrationService;
use app\forms\UserRegistrationForm;
use app\controllers\UserController;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    EmailsFixture,
    UsersFixture};
use yii\helpers\Url;

/**
 * Тестирует класс UserRegistrationService
 */
class UserRegistrationServiceTests extends TestCase
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
     * Тестирует свойства UserRegistrationService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRegistrationService::class);
        
        $this->assertTrue($reflection->hasProperty('userRegistrationArray'));
        $this->assertTrue($reflection->hasProperty('form'));
    }
    
    /**
     * Тестирует метод UserRegistrationService::getUserRegistrationArray
     */
    public function testGetUserRegistrationArray()
    {
        $service = new UserRegistrationService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new class() extends UserRegistrationForm {});
        
        $reflection = new \ReflectionMethod($service, 'getUserRegistrationArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(UserRegistrationForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }

    /**
     * Тестирует метод UserRegistrationService::handle
     * если GET
     */
    public function testHandleGet()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);

        $request = new class() {
            public $isPost = false;
        };

        $service = new UserRegistrationService();
        
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
     * Тестирует метод UserRegistrationService::handle
     * если POST
     */
    public function testHandlePost()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);

        $request = new class() {
            public $isPost = true;
            public $email;
            public $password;
            public $password2;
            public function post()
            {
                return [
                    'UserRegistrationForm'=>[
                        'email'=>'some@gmail.com',
                        'password'=>'password',
                        'password2'=>'password',
                    ],
                ];
            }
        };
        $service = new UserRegistrationService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertSame(Url::to(['/user/login']), $result);
    }

    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
