<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminUserPasswordRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\models\UsersModel;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminUserPasswordRequestHandler
 */
class AdminUserPasswordRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminUserPasswordRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminUserPasswordRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserPasswordRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminUserPasswordRequestHandler::adminChangeUserPasswordWidgetConfig
     */
    public function testAdminChangeUserPasswordWidgetConfig()
    {
        $userChangePasswordForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminChangeUserPasswordWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $userChangePasswordForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminUserPasswordRequestHandler::handle
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
        
        $this->assertArrayHasKey('adminChangeUserPasswordWidgetConfig', $result);
        $this->assertArrayHasKey('adminUserDetailBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('adminUserMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminChangeUserPasswordWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserDetailBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserMenuWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
