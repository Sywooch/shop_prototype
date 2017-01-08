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
use app\collections\{LightPagination,
    ProductsCollection};
use app\models\CurrencyModel;
use app\forms\FiltersForm;
use app\helpers\HashHelper;
use yii\helpers\Url;
use yii\web\Request;

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
        
        $this->assertTrue($reflection->hasProperty('breadcrumbsArray'));
        $this->assertTrue($reflection->hasProperty('sphinxArray'));
        $this->assertTrue($reflection->hasProperty('emptySphinxArray'));
        $this->assertTrue($reflection->hasProperty('productsCollection'));
        $this->assertTrue($reflection->hasProperty('filtersArray'));
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getBreadcrumbsArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetBreadcrumbsArrayEmptyRequest()
    {
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getBreadcrumbsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getBreadcrumbsArray
     */
    public function testGetBreadcrumbsArray()
    {
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'пиджак';
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getBreadcrumbsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertInternalType('string', $result['text']);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getSphinxArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetSphinxArrayEmptyRequest()
    {
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getSphinxArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getSphinxArray
     */
    public function testGetSphinxArray()
    {
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'пиджак';
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getSphinxArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getEmptySphinxArray
     */
    public function testGetEmptySphinxArray()
    {
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getEmptySphinxArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getProductsCollection
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetProductsCollectionEmptyRequest()
    {
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getProductsCollection
     */
    public function testGetProductsCollection()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'пиджак';
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInstanceOf(ProductsCollection::class, $result);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getFiltersArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetFiltersArrayEmptyRequest()
    {
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getFiltersArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::getFiltersArray
     */
    public function testGetFiltersArray()
    {
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'пиджак';
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'getFiltersArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'handle');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если sphinx ничего не нашел
     */
    public function testHandleEmptySphinx()
    {
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'abrakadabra';
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'handle');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('emptySphinxConfig', $result);
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        
        $this->assertArrayNotHasKey('emptyConfig', $result);
        $this->assertArrayNotHasKey('productsConfig', $result);
        $this->assertArrayNotHasKey('paginationConfig', $result);
        $this->assertArrayNotHasKey('filtersConfig', $result);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если запрошена несуществующая страница
     * @expectedException \yii\web\NotFoundHttpException
     * @expectedExceptionMessage Такой страницы не существует
     */
    public function testHandle404()
    {
        $request = new class() extends Request {
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
        
        $reflection = new \ReflectionMethod($service, 'handle');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
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
        
        $request = new class() extends Request {
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
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('emptyConfig', $result);
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        
        $this->assertArrayNotHasKey('emptySphinxConfig', $result);
        $this->assertArrayNotHasKey('productsConfig', $result);
        $this->assertArrayNotHasKey('paginationConfig', $result);
        
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
        
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'search') {
                    return 'пиджак';
                }
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionMethod($service, 'handle');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('productsConfig', $result);
        $this->assertArrayHasKey('paginationConfig', $result);
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        
        $this->assertArrayNotHasKey('emptySphinxConfig', $result);
        $this->assertArrayNotHasKey('emptyConfig', $result);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
