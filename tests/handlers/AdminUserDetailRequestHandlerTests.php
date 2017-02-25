<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminUserDetailRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    MailingsFixture,
    PurchasesFixture,
    UsersFixture};
use app\models\{CurrencyInterface,
    CurrencyModel,
    UsersModel};

/**
 * Тестирует класс AdminUserDetailRequestHandler
 */
class AdminUserDetailRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'orders'=>PurchasesFixture::class,
                'mailings'=>MailingsFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminUserDetailRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminUserDetailRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserDetailRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminUserDetailRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return 1;
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('accountContactsWidgetConfig', $result);
        $this->assertArrayhasKey('accountCurrentOrdersWidgetConfig', $result);
        $this->assertArrayhasKey('accountMailingsWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserDetailBreadcrumbsWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountContactsWidgetConfig']);
        $this->assertInternalType('array', $result['accountCurrentOrdersWidgetConfig']);
        $this->assertInternalType('array', $result['accountMailingsWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserDetailBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserMenuWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
