<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\helpers\Url;
use app\handlers\ModProductsListIndexRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    ProductsFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\{CategoriesModel,
    SubcategoryModel};
use app\forms\AbstractBaseForm;
use app\controllers\ProductsListController;
use app\helpers\HashHelper;

/**
 * Тестирует класс ModProductsListIndexRequestHandler
 */
class ModProductsListIndexRequestHandlerTests extends TestCase
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
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductsListController('list', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new ModProductsListIndexRequestHandler();
    }
    
    /**
     * Тестирует свойства ModProductsListIndexRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModProductsListIndexRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод ModProductsListIndexRequestHandler::categoriesBreadcrumbsWidgetConfig
     */
    public function testCategoriesBreadcrumbsWidgetConfig()
    {
        $categoriesModel = new class() extends CategoriesModel {};
        $subcategoryModel = new class() extends SubcategoryModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'categoriesBreadcrumbsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesModel, $subcategoryModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('category', $result);
        $this->assertArrayHasKey('subcategory', $result);
        
        $this->assertInstanceOf(CategoriesModel::class, $result['category']);
        $this->assertInstanceOf(SubcategoryModel::class, $result['subcategory']);
    }
    
    /**
     * Тестирует метод ModProductsListIndexRequestHandler::filtersWidgetConfig
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
        $sortingFieldsArray = [new class() {}];
        $filtersForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'filtersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $colorsArray, $sizesArray, $sortingFieldsArray, $filtersForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ModProductsListIndexRequestHandler::handle
     * category === true
     * subcategory === true
     * page === 0
     */
    public function testHandleCategory()
    {
        $request = new class() {
            public $category;
            public $subcategory;
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'category':
                        return $this->category;
                    case 'subcategory':
                        return $this->subcategory;
                    default:
                        return null;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($request, self::$dbClass->categories['category_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($request, self::$dbClass->subcategory['subcategory_2']['seocode']);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('productsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        $this->assertArrayHasKey('frontendFooterWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['productsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
        $this->assertInternalType('array', $result['frontendFooterWidgetConfig']);
    }
    
    /**
     * Тестирует метод ModProductsListIndexRequestHandler::handle
     * category === null
     * subcategory === null
     * page === true
     */
    public function testHandlePage()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'page':
                        return 2;
                    default:
                        return null;
                }
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('productsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        $this->assertArrayHasKey('frontendFooterWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['productsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
        $this->assertInternalType('array', $result['frontendFooterWidgetConfig']);
    }
    
    /**
     * Тестирует метод ModProductsListIndexRequestHandler::handle
     * несуществующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotPage()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'page':
                        return 200;
                    default:
                        return null;
                }
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод ModProductsListIndexRequestHandler::handle
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
                return null;
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('emptyProductsWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        $this->assertArrayHasKey('frontendFooterWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('productsWidgetConfig', $result);
        $this->assertArrayNotHasKey('paginationWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['emptyProductsWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
        $this->assertInternalType('array', $result['frontendFooterWidgetConfig']);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
