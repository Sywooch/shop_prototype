<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminUserDataRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\models\UsersModel;

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
        
        $this->assertArrayhasKey('accountChangeDataWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserDetailBreadcrumbsWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountChangeDataWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserDetailBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserMenuWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
