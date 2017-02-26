<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminUserSubscriptionsRequestHandler;
use app\models\UsersModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    MailingsFixture,
    UsersFixture};
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminUserSubscriptionsRequestHandler
 */
class AdminUserSubscriptionsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'mailings'=>MailingsFixture::class,
                'emails'=>EmailsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminUserSubscriptionsRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminUserSubscriptionsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserSubscriptionsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminUserSubscriptionsRequestHandler::handle
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
        
        $this->assertArrayHasKey('adminUserMailingsUnsubscribeWidgetConfig', $result);
        $this->assertArrayHasKey('adminUserMailingsFormWidgetConfig', $result);
        $this->assertArrayHasKey('adminUserDetailBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('adminUserMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminUserMailingsUnsubscribeWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserMailingsFormWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserDetailBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserMenuWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
