<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminCommentsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\forms\AbstractBaseForm;
use app\controllers\AdminController;

/**
 * Тестирует класс AdminCommentsRequestHandler
 */
class AdminCommentsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCommentsRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminCommentsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminCommentsRequestHandler::adminCommentsWidgetConfig
     */
    public function testAdminCommentsWidgetConfig()
    {
        $commentsModelArray = [new class() {}];
        $commentForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCommentsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $commentsModelArray, $commentForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('comments', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['comments']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCommentsRequestHandler::adminCommentsFiltersWidgetConfig
     */
    public function testAdminCommentsFiltersWidgetConfig()
    {
        $sortingFieldsArray = [new class() {}];
        $sortingTypesArray = [new class() {}];
        $activeStatusesArray = [new class() {}];
        $adminCommentsFiltersForm = new class() extends AbstractBaseForm {
            public $sortingField;
            public $sortingType;
            public $activeStatuse;
            public $url;
        };
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCommentsFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $sortingFieldsArray, $sortingTypesArray, $activeStatusesArray, $adminCommentsFiltersForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('activeStatuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['activeStatuses']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCommentsRequestHandler::adminCsvCommentsFormWidgetConfig
     */
    public function testAdminCsvCommentsFormWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'adminCsvCommentsFormWidgetConfig');
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
     * Тестирует метод AdminCommentsRequestHandler::handle
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
        
        $this->assertArrayHasKey('adminCommentsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('adminCommentsFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('adminCsvCommentsFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminCommentsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['adminCommentsFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['adminCsvCommentsFormWidgetConfig']);
    }
    
    /**
     * Тестирует метод AdminCommentsRequestHandler::handle
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
        
        $this->assertArrayHasKey('adminCommentsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('adminCommentsFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('adminCsvCommentsFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminCommentsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['adminCommentsFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['adminCsvCommentsFormWidgetConfig']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::handle
     * не существующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotPage()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 500;
            }
        };
        
        $this->handler->handle($request);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
