<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminUserDataRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\models\UsersModel;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminUserDataRequestHandler
 */
class AdminUserDataRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'emails'=>EmailsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminUserDataRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminUserDataRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserDataRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminUserDataRequestHandler::adminChangeUserDataWidgetConfig
     */
    public function testAdminChangeUserDataWidgetConfig()
    {
        $userUpdateForm = new class() extends AbstractBaseForm {
            public $name;
            public $surname;
            public $phone;
            public $address;
            public $city;
            public $country;
            public $postcode;
        };
        
        $usersModel = UsersModel::findOne(1);
        
        $reflection = new \ReflectionMethod($this->handler, 'accountChangeDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $userUpdateForm, $usersModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminUserDataRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $email;
            public function get($name=null, $defaultValue=null)
            {
                return $this->email;
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('adminChangeUserDataWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserDetailBreadcrumbsWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminChangeUserDataWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserDetailBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserMenuWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
