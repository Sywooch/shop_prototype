<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminProductsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    CurrencyFixture,
    ProductsFixture,
    SizesFixture};
use app\controllers\AdminController;
use app\forms\{AdminProductForm,
    AdminProductsFiltersForm};
use app\collections\AbstractBaseCollection;
use app\models\CurrencyModel;
use app\collections\{LightPagination,
    PaginationInterface};

/**
 * Тестирует класс AdminProductsRequestHandler
 */
class AdminProductsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'brands'=>BrandsFixture::class,
                'categories'=>CategoriesFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства AdminProductsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::adminProductsFiltersWidgetConfig
     */
    public function testAdminProductsFiltersWidgetConfig()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $handler = new AdminProductsRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminProductsFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('subcategory', $result);
        $this->assertArrayHasKey('activeStatuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('array', $result['subcategory']);
        $this->assertInternalType('array', $result['activeStatuses']);
        $this->assertInstanceOf(AdminProductsFiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::adminProductsWidgetConfig
     * если данных нет
     */
    public function testAdminProductsWidgetConfigEmpty()
    {
        $collection = new class() extends AbstractBaseCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new class() {
                    public $totalCount = 0;
                };
            }
            public function isEmpty()
            {
                return true;
            }
            public function asArray()
            {
                return [];
            }
        };
        
        $handler = new AdminProductsRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $collection);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(AdminProductForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::adminProductsWidgetConfig
     * если зарпосил несуществующую страницу
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testAdminProductsWidgetConfigNotPage()
    {
        $collection = new class() extends AbstractBaseCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new class() {
                    public $totalCount = 10;
                };
            }
            public function isEmpty()
            {
                return true;
            }
            public function asArray()
            {
                return [];
            }
        };
        
        $handler = new AdminProductsRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminProductsWidgetConfig');
        $reflection->setAccessible(true);
        $reflection->invoke($handler, $collection);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::adminProductsWidgetConfig
     */
    public function testAdminProductsWidget()
    {
        $collection = new class() extends AbstractBaseCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new class() {
                    public $totalCount = 2;
                };
            }
            public function isEmpty()
            {
                return false;
            }
            public function asArray()
            {
                return [];
            }
        };
        
        $handler = new AdminProductsRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $collection);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(AdminProductForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::paginationWidgetConfig
     */
    public function testPaginationWidgetConfig()
    {
        $collection = new class() extends AbstractBaseCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new class() extends LightPagination {};
            }
        };
        
        $handler = new AdminProductsRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'paginationWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $collection);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::adminCsvProductsFormWidgetConfig
     */
    public function testAdminCsvProductsFormWidgetConfig()
    {
        $collection = new class() extends AbstractBaseCollection {
            public function isEmpty()
            {
                return false;
            }
        };
        
        $handler = new AdminProductsRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminCsvProductsFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $collection);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        $this->assertArrayHasKey('isAllowed', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
        $this->assertInternalType('boolean', $result['isAllowed']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminProductsRequestHandler();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AdminProductsRequestHandler();
        $result = $service->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminProductsFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('adminProductsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('adminCsvProductsFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminProductsFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['adminProductsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['adminCsvProductsFormWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
