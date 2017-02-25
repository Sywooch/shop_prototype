<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminUsersRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\forms\AbstractBaseForm;
use app\controllers\AdminController;

/**
 * Тестирует класс AdminUsersRequestHandler
 */
class AdminUsersRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminUsersRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminUsersRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUsersRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminUsersRequestHandler::adminUsersWidgetConfig
     */
    public function testAdminUsersWidgetConfig()
    {
        $usersModelArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'adminUsersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $usersModelArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('users', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['users']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminUsersRequestHandler::usersFiltersWidgetConfig
     */
    public function testUsersFiltersWidgetConfig()
    {
        $sortingFieldsArray = [new class() {}];
        $sortingTypesArray = [new class() {}];
        $ordersStatusesArray = [new class() {}];
        $usersFiltersForm = new class() extends AbstractBaseForm {
            public $sortingField;
            public $sortingType;
            public $ordersStatus;
            public $url;
        };
        
        $reflection = new \ReflectionMethod($this->handler, 'usersFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $sortingFieldsArray, $sortingTypesArray, $ordersStatusesArray, $usersFiltersForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('ordersStatuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['ordersStatuses']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminUsersRequestHandler::adminCsvUsersFormWidgetConfig
     */
    public function testAdminCsvUsersFormWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'adminCsvUsersFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, true);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        $this->assertArrayHasKey('isAllowed', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
        $this->assertInternalType('boolean', $result['isAllowed']);
    }
    
    /**
     * Тестирует метод AdminUsersRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminUsersWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('usersFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('adminCsvUsersFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminUsersWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['usersFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['adminCsvUsersFormWidgetConfig']);
    }
    
    /**
     * Тестирует метод AdminUsersRequestHandler::handle
     * если передана страница
     */
    public function testHandlePage()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 2;
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminUsersWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('usersFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('adminCsvUsersFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminUsersWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['usersFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['adminCsvUsersFormWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
