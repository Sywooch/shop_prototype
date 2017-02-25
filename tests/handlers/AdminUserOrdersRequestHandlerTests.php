<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminUserOrdersRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    EmailsFixture,
    PurchasesFixture,
    UsersFixture};
use app\controllers\AdminController;
use app\models\UsersModel;

/**
 * Тестирует класс AdminUserOrdersRequestHandler
 */
class AdminUserOrdersRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'users'=>UsersFixture::class,
                'orders'=>PurchasesFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminUserOrdersRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminUserOrdersRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserOrdersRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminUserOrdersRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $email;
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'email':
                        return $this->email;
                        break;
                    case 'page':
                        return null;
                        break;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('оrdersFiltersWidgetConfig', $result);
        $this->assertArrayhasKey('accountOrdersWidgetConfig', $result);
        $this->assertArrayhasKey('paginationWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserDetailBreadcrumbsWidgetConfig', $result);
        $this->assertArrayhasKey('adminUserMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['оrdersFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['accountOrdersWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserDetailBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['adminUserMenuWidgetConfig']);
    }
    
    /**
     * Тестирует метод AdminUserOrdersRequestHandler::handle
     * не существующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotPage()
    {
        $request = new class() {
            public $email;
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'email':
                        return $this->email;
                        break;
                    case 'page':
                        return 402;
                        break;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $this->handler->handle($request);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
