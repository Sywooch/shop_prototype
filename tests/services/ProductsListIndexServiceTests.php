<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\services\ProductsListIndexService;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture,
    SubcategoryFixture};
use app\controllers\ProductsListController;
use app\helpers\HashHelper;
use yii\helpers\Url;

/**
 * Тестирует класс ProductsListIndexService
 */
class ProductsListIndexServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'colors'=>ColorsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'brands'=>BrandsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsListIndexService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsListIndexService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод ProductsListIndexService::handle
     * если запрошена несуществующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandle404()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public $category;
            public $subcategory;
            public $page;
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'category') {
                    return $this->category;
                }
                if ($name === 'subcategory') {
                    return $this->subcategory;
                }
                if ($name === 'page') {
                    return $this->page;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'category');
        $reflection->setValue($request, self::$dbClass->categories['category_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'subcategory');
        $reflection->setValue($request, self::$dbClass->subcategory['subcategory_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'page');
        $reflection->setValue($request, 20);
        
        $service = new ProductsListIndexService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод ProductsListIndexService::handle
     * если нет товаров, соответствующих фильтрам
     */
    public function testHandleEmptyCollection()
    {
        \Yii::$app->registry->clean();
        
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $key = HashHelper::createFiltersKey(Url::current());
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->set($key, [
            'colors'=>[112, 154],
            'sizes'=>[133, 217],
            'brands'=>[125],
        ]);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new ProductsListIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('cartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('emptyProductsWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('productsWidgetConfig', $result);
        $this->assertArrayNotHasKey('paginationWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['cartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['emptyProductsWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
        
        $session->remove($key);
        $session->close();
    }
    
    /**
     * Тестирует метод ProductsListIndexService::handle
     */
    public function testHandle()
    {
        \Yii::$app->registry->clean();
        
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $key = HashHelper::createFiltersKey(Url::current());
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->set($key, [
            'colors'=>[1, 2, 3],
            'sizes'=>[1, 2, 3],
            'brands'=>[1, 2, 3],
        ]);
        
        $request = new class() {
            public $category;
            public $subcategory;
            public $page;
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'category') {
                    return $this->category;
                }
                if ($name === 'subcategory') {
                    return $this->subcategory;
                }
                if ($name === 'page') {
                    return $this->page;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'category');
        $reflection->setValue($request, self::$dbClass->categories['category_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'subcategory');
        $reflection->setValue($request, self::$dbClass->subcategory['subcategory_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'page');
        $reflection->setValue($request, 1);
        
        $service = new ProductsListIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('cartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('productsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
