<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserRecoveryService;
use app\forms\RecoveryPasswordForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    EmailsFixture,
    UsersFixture};
use app\controllers\UserController;

/**
 * Тестирует класс UserRecoveryService
 */
class UserRecoveryServiceTests extends TestCase
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
     * Тестирует свойства UserRecoveryService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRecoveryService::class);
        
        $this->assertTrue($reflection->hasProperty('userRecoveryArray'));
        $this->assertTrue($reflection->hasProperty('userRecoverySuccessArray'));
        $this->assertTrue($reflection->hasProperty('form'));
    }
    
    /**
     * Тестирует метод UserRecoveryService::getUserRecoveryArray
     */
    public function testGetUserRecoveryArray()
    {
        $service = new UserRecoveryService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new class() extends RecoveryPasswordForm {});
        
        $reflection = new \ReflectionMethod($service, 'getUserRecoveryArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(RecoveryPasswordForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод UserRecoveryService::getUserRecoverySuccessArray
     */
    public function testGetUserRecoverySuccessArray()
    {
        $form = new class() extends RecoveryPasswordForm {
            public $email = 'some@some.com';
        };
        
        $service = new UserRecoveryService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $form);
        
        $reflection = new \ReflectionMethod($service, 'getUserRecoverySuccessArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод UserRecoveryService::handle
     * если AJAX
     */
    public function testHandleAjax()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $request = new class() {
            public $isAjax = true;
            public function post()
            {
                return [
                    'RecoveryPasswordForm'=>[
                        'email'=>'some@gmail',
                    ],
                ];
            }
        };
        
        $service = new UserRecoveryService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UserRecoveryService::handle
     * если GET
     */
    public function testHandleGet()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);

        $request = new class() {
            public $isPost = false;
            public $isAjax = false;
        };

        $service = new UserRecoveryService();
        $result = $service->handle($request);

        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('formConfig', $result);
        
        $this->assertArrayNotHasKey('successConfig', $result);

        $this->assertInternalType('array', $result['userConfig']);
        $this->assertInternalType('array', $result['cartConfig']);
        $this->assertInternalType('array', $result['currencyConfig']);
        $this->assertInternalType('array', $result['searchConfig']);
        $this->assertInternalType('array', $result['menuConfig']);
        $this->assertInternalType('array', $result['formConfig']);
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertEmpty($files);
    }
    
    /**
     * Тестирует метод UserRecoveryService::handle
     * если POST
     */
    public function testHandlePost()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $emailFixture = self::$dbClass->emails['email_1'];
        
        $request = new class() {
            public $isPost = true;
            public $isAjax = false;
            public $email;
            public function post()
            {
                return [
                    'RecoveryPasswordForm'=>[
                        'email'=>$this->email,
                    ],
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, $emailFixture['email']);
        
        $service = new UserRecoveryService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('successConfig', $result);
        
        $this->assertArrayNotHasKey('formConfig', $result);
        
        $this->assertInternalType('array', $result['userConfig']);
        $this->assertInternalType('array', $result['cartConfig']);
        $this->assertInternalType('array', $result['currencyConfig']);
        $this->assertInternalType('array', $result['searchConfig']);
        $this->assertInternalType('array', $result['menuConfig']);
        $this->assertInternalType('array', $result['successConfig']);
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertNotEmpty($files);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        if (file_exists($saveDir) && is_dir($saveDir)) {
            $files = glob($saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
