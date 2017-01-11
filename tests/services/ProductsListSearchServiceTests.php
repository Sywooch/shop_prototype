<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\services\ProductsListSearchService;
use app\tests\sources\fixtures\{BrandsFixture,
    ColorsFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture};
use app\controllers\ProductsListController;
use app\helpers\HashHelper;
use yii\helpers\Url;

/**
 * Тестирует класс ProductsListSearchService
 */
class ProductsListSearchServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
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
     * Тестирует свойства ProductsListSearchService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsListSearchService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleErrorRequest()
    {
        $service = new ProductsListSearchService();
        $service->handle();
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если $request пуст
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new ProductsListSearchService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если sphinx ничего не нашел
     */
    public function testHandleEmptySphinx()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 'abrakadabra';
            }
        };
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('emptySphinxWidgetConfig', $result);
        $this->assertArrayHasKey('searchBreadcrumbsWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        $this->assertArrayNotHasKey('productsWidgetConfig', $result);
        $this->assertArrayNotHasKey('paginationWidgetConfig', $result);
        $this->assertArrayNotHasKey('filtersWidgetConfig', $result);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если запрошена несуществующая страница
     * @expectedException \yii\web\NotFoundHttpException
     * @expectedExceptionMessage Такой страницы не существует
     */
    public function testHandle404()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'search') {
                    return 'пиджак';
                }
                if ($name === 'page') {
                    return 14;
                }
            }
        };
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если после применения фильтров ничего не найдено
     */
    public function testHandleTooMuchFilters()
    {
        $finder = \Yii::$app->registry->clean();
        
        $key = HashHelper::createFiltersKey(Url::current());
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'colors'=>[12],
            'sizes'=>[77],
            'brands'=>[9],
        ]);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'search') {
                    return 'пиджак';
                }
                if ($name === 'page') {
                    return 1;
                }
            }
        };
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('emptyProductsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        $this->assertArrayHasKey('searchBreadcrumbsWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptySphinxWidgetConfig', $result);
        $this->assertArrayNotHasKey('productsWidgetConfig', $result);
        $this->assertArrayNotHasKey('paginationWidgetConfig', $result);
        
        $session->remove($key);
        $session->close();
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     */
    public function testHandle()
    {
        $finder = \Yii::$app->registry->clean();
        
        $key = HashHelper::createFiltersKey(Url::current());
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'colors'=>[1, 2, 3, 4, 5],
            'sizes'=>[1, 2, 3, 4, 5],
            'brands'=>[1, 2, 3, 4, 5],
        ]);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'search') {
                    return 'пиджак';
                }
            }
        };
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('productsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        $this->assertArrayHasKey('searchBreadcrumbsWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptySphinxWidgetConfig', $result);
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
