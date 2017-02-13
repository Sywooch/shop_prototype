<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\helpers\Url;
use app\handlers\ProductsListSearchRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    ProductsFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\{CategoriesModel,
    SubcategoryModel};
use app\forms\{AbstractBaseForm,
    FiltersForm};
use app\controllers\ProductsListController;
use app\filters\ProductsFilters;
use app\helpers\HashHelper;

/**
 * Тестирует класс ProductsListSearchRequestHandler
 */
class ProductsListSearchRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'brands'=>BrandsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductsListController('list', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new ProductsListSearchRequestHandler();
    }
    
    /**
     * Тестирует свойства ProductsListSearchRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsListSearchRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::emptySphinxWidgetConfig
     */
    public function testEmptySphinxWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'emptySphinxWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::filtersWidgetConfig
     */
    public function testFiltersWidgetConfig()
    {
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
        $sortingFieldsArray = [new class() {}];
        $sortingTypesArray = [new class() {}];
        $filtersForm = new class() extends AbstractBaseForm {};
        
        $handler = new ProductsListSearchRequestHandler();
        
        $reflection = new \ReflectionMethod($this->handler, 'filtersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $colorsArray, $sizesArray, $brandsArray, $sortingFieldsArray, $sortingTypesArray, $filtersForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::searchBreadcrumbsWidgetConfig
     */
    public function testSearchBreadcrumbsWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'searchBreadcrumbsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, 'coats');
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('text', $result);
        $this->assertInternalType('string', $result['text']);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::handle
     * searchText === true
     * page === 0
     * фраза не найдена
     */
    public function testHandleEmptySearchResult()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'search':
                        return 'nothing';
                    default:
                        return null;
                }
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('emptySphinxWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('searchBreadcrumbsWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        $this->assertArrayNotHasKey('productsWidgetConfig', $result);
        $this->assertArrayNotHasKey('paginationWidgetConfig', $result);
        $this->assertArrayNotHasKey('filtersWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['emptySphinxWidgetConfig']);
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['searchBreadcrumbsWidgetConfig']);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::handle
     * searchText === true
     * page === true
     * фраза найдена
     */
    public function testHandleSearchResult()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'search':
                        return 'пиджак';
                    default:
                        return 2;
                }
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('productsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('searchBreadcrumbsWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptySphinxWidgetConfig', $result);
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['productsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['searchBreadcrumbsWidgetConfig']);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::handle
     * не существующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotFound()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'search':
                        return 'пиджак';
                    default:
                        return 200;
                }
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::handle
     * нет товаров
     */
    public function testHandleNotProducts()
    {
        $key = HashHelper::createFiltersKey(Url::current());
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['colors'=>[123, 12], 'sizes'=>[44]]);
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'search':
                        return 'пиджак';
                    default:
                        return null;
                }
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('emptyProductsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('searchBreadcrumbsWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptySphinxWidgetConfig', $result);
        $this->assertArrayNotHasKey('productsWidgetConfig', $result);
        $this->assertArrayNotHasKey('paginationWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['emptyProductsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['searchBreadcrumbsWidgetConfig']);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
