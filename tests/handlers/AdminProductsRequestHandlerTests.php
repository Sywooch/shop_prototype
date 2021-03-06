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
use app\forms\{AbstractBaseForm,
    AdminProductForm,
    AdminProductsFiltersForm};
use app\collections\{AbstractBaseCollection,
    LightPagination,
    PaginationInterface};
use app\models\{CurrencyInterface,
    CurrencyModel};

/**
 * Тестирует класс AdminProductsRequestHandler
 */
class AdminProductsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
        
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminProductsRequestHandler();
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
        $sortingFieldsArray = [new class() {}];
        $sortingTypesArray = [new class() {}];
        $colorsArray = [new class() {
            public $id = 1;
            public $color = 'color';
        }];
        $sizesArray = [new class() {
            public $id = 1;
            public $size = 'size';
        }];
        $brandsArray = [new class() {
            public $id = 1;
            public $brand = 'brand';
        }];
        $categoriesArray = [new class() {
            public $id = 1;
            public $name = 'name';
        }];
        $subcategoryArray = [new class() {
            public $id = 1;
            public $name = 'name';
        }];
        $activeStatusesArray = [new class() {}];
        $adminProductsFiltersForm = new class() extends AbstractBaseForm {
            public $sortingField;
            public $sortingType;
            public $url;
        };
        
        $reflection = new \ReflectionMethod($this->handler, 'adminProductsFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $sortingFieldsArray, $sortingTypesArray, $colorsArray, $sizesArray, $brandsArray, $categoriesArray, $subcategoryArray, $activeStatusesArray, $adminProductsFiltersForm);
        
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
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::adminProductsWidgetConfig
     * если данных нет
     */
    public function testAdminProductsWidgetConfigEmpty()
    {
        $productsArray = [new class() {}];
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $adminProductForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsArray, $currentCurrencyModel, $adminProductForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['products']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductsRequestHandler::adminCsvProductsFormWidgetConfig
     */
    public function testAdminCsvProductsFormWidgetConfig()
    {
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCsvProductsFormWidgetConfig');
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
        
        $result = $this->handler->handle($request);

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
