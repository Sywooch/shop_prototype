<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserRegistrationService;
use app\forms\UserRegistrationForm;
use app\controllers\UserController;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    UsersFixture};
use yii\helpers\Url;
use app\models\EmailsModel;

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
        $this->assertTrue($reflection->hasProperty('userRegistrationSuccessArray'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('userLoginArray'));
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
     * Тестирует метод UserRegistrationService::getUserRegistrationSuccessArray
     */
    public function testGetUserRegistrationSuccessArray()
    {
        $service = new UserRegistrationService();
        
        $reflection = new \ReflectionMethod($service, 'getUserRegistrationSuccessArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод UserRegistrationService::handle
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
                    'UserRegistrationForm'=>[
                        'email'=>'some@gmail.com',
                        'password'=>'password',
                        'password2'=>'password2',
                    ],
                ];
            }
        };
        
        $service = new UserRegistrationService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
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
            public $isAjax = false;
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
     * Тестирует метод UserRegistrationService::handle
     * если POST
     */
    public function testHandlePost()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);

        $request = new class() {
            public $isPost = true;
            public $isAjax = false;
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
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} INNER JOIN {{emails}} ON [[users.id_email]]=[[emails.id]] WHERE [[emails.email]]=:email')->bindValue(':email', 'some@gmail.com')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
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
