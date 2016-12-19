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
     * Тестирует метод ProductsListSearchService::handle
     * если поисковый запрос пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: searchKey
     */
    public function testHandleEmptySearch()
    {
        $request = [];
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * страница === 0, фильтры отсутствуют,
     * поиск не дал результатов
     */
    public function testHandleNotFound()
    {
         \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [\Yii::$app->params['searchKey']=>'то чего нет'];
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('text', $result['breadcrumbsConfig']);
        $this->assertInternalType('string', $result['breadcrumbsConfig']['text']);
        
        $this->assertArrayHasKey('emptySphinxConfig', $result);
        $this->assertArrayHasKey('view', $result['emptySphinxConfig']);
        $this->assertInternalType('string', $result['emptySphinxConfig']['view']);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * страница === 0, фильтры отсутствуют,
     * выборка не пуста
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [\Yii::$app->params['searchKey']=>'пиджак'];
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('text', $result['breadcrumbsConfig']);
        $this->assertInternalType('string', $result['breadcrumbsConfig']['text']);
        
        $this->assertArrayNotHasKey('emptySphinxConfig', $result);
        
        $this->assertArrayNotHasKey('emptyConfig', $result);
        
        $this->assertArrayHasKey('productsConfig', $result);
        $this->assertArrayHasKey('products', $result['productsConfig']);
        $this->assertArrayHasKey('currency', $result['productsConfig']);
        $this->assertArrayHasKey('view', $result['productsConfig']);
        $this->assertInstanceOf(ProductsCollection::class, $result['productsConfig']['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['productsConfig']['currency']);
        $this->assertInternalType('string', $result['productsConfig']['view']);
        
        $this->assertArrayHasKey('paginationConfig', $result);
        $this->assertArrayHasKey('pagination', $result['paginationConfig']);
        $this->assertArrayHasKey('view', $result['paginationConfig']);
        $this->assertInstanceOf(LightPagination::class, $result['paginationConfig']['pagination']);
        $this->assertInternalType('string', $result['paginationConfig']['view']);
        
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('colors', $result['filtersConfig']);
        $this->assertArrayHasKey('sizes', $result['filtersConfig']);
        $this->assertArrayHasKey('brands', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingFields', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingTypes', $result['filtersConfig']);
        $this->assertArrayHasKey('form', $result['filtersConfig']);
        $this->assertArrayHasKey('view', $result['filtersConfig']);
        $this->assertInternalType('array', $result['filtersConfig']['colors']);
        $this->assertInternalType('array', $result['filtersConfig']['sizes']);
        $this->assertInternalType('array', $result['filtersConfig']['brands']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingFields']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['filtersConfig']['form']);
        $this->assertInternalType('string', $result['filtersConfig']['view']);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * страница === 0, фильтры отсутствуют,
     * выборка пуста
     */
    public function testHandleEmptyProducts()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [\Yii::$app->params['searchKey']=>'пиджак'];
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set(HashHelper::createFiltersKey(), [
            'colors'=>[18],
            'sizes'=>[15],
            'brands'=>[10],
        ]);
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('text', $result['breadcrumbsConfig']);
        $this->assertInternalType('string', $result['breadcrumbsConfig']['text']);
        
        $this->assertArrayHasKey('emptyConfig', $result);
        $this->assertArrayHasKey('view', $result['emptyConfig']);
        $this->assertInternalType('string', $result['emptyConfig']['view']);
        
        $this->assertArrayNotHasKey('productsConfig', $result);
        
        $this->assertArrayHasKey('paginationConfig', $result);
        $this->assertArrayHasKey('pagination', $result['paginationConfig']);
        $this->assertArrayHasKey('view', $result['paginationConfig']);
        $this->assertInstanceOf(LightPagination::class, $result['paginationConfig']['pagination']);
        $this->assertInternalType('string', $result['paginationConfig']['view']);
        
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('colors', $result['filtersConfig']);
        $this->assertArrayHasKey('sizes', $result['filtersConfig']);
        $this->assertArrayHasKey('brands', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingFields', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingTypes', $result['filtersConfig']);
        $this->assertArrayHasKey('form', $result['filtersConfig']);
        $this->assertArrayHasKey('view', $result['filtersConfig']);
        $this->assertInternalType('array', $result['filtersConfig']['colors']);
        $this->assertInternalType('array', $result['filtersConfig']['sizes']);
        $this->assertInternalType('array', $result['filtersConfig']['brands']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingFields']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['filtersConfig']['form']);
        $this->assertInternalType('string', $result['filtersConfig']['view']);
        
        $session->remove(HashHelper::createFiltersKey());
        $session->close();
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * страница === 0, заданы,
     * выборка не пуста
     */
    public function testHandleFilters()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [\Yii::$app->params['searchKey']=>'пиджак'];
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set(HashHelper::createFiltersKey(), [
            'colors'=>[1, 2, 3, 4, 5, 6],
            'sizes'=>[1, 2, 3, 4, 5, 6],
            'brands'=>[1, 2, 3, 4, 5, 6],
        ]);
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('text', $result['breadcrumbsConfig']);
        $this->assertInternalType('string', $result['breadcrumbsConfig']['text']);
        
        $this->assertArrayNotHasKey('emptySphinxConfig', $result);
        
        $this->assertArrayNotHasKey('emptyConfig', $result);
        
        $this->assertArrayHasKey('productsConfig', $result);
        $this->assertArrayHasKey('products', $result['productsConfig']);
        $this->assertArrayHasKey('currency', $result['productsConfig']);
        $this->assertArrayHasKey('view', $result['productsConfig']);
        $this->assertInstanceOf(ProductsCollection::class, $result['productsConfig']['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['productsConfig']['currency']);
        $this->assertInternalType('string', $result['productsConfig']['view']);
        
        $this->assertArrayHasKey('paginationConfig', $result);
        $this->assertArrayHasKey('pagination', $result['paginationConfig']);
        $this->assertArrayHasKey('view', $result['paginationConfig']);
        $this->assertInstanceOf(LightPagination::class, $result['paginationConfig']['pagination']);
        $this->assertInternalType('string', $result['paginationConfig']['view']);
        
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('colors', $result['filtersConfig']);
        $this->assertArrayHasKey('sizes', $result['filtersConfig']);
        $this->assertArrayHasKey('brands', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingFields', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingTypes', $result['filtersConfig']);
        $this->assertArrayHasKey('form', $result['filtersConfig']);
        $this->assertArrayHasKey('view', $result['filtersConfig']);
        $this->assertInternalType('array', $result['filtersConfig']['colors']);
        $this->assertInternalType('array', $result['filtersConfig']['sizes']);
        $this->assertInternalType('array', $result['filtersConfig']['brands']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingFields']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['filtersConfig']['form']);
        $this->assertInternalType('string', $result['filtersConfig']['view']);
        
        $session->remove(HashHelper::createFiltersKey());
        $session->close();
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * задана страница, фильтры отсутствуют,
     * выборка не пуста
     */
    public function testHandlePage()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [
            \Yii::$app->params['searchKey']=>'пиджак',
            \Yii::$app->params['pagePointer']=>2,
        ];
        
        $service = new ProductsListSearchService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('text', $result['breadcrumbsConfig']);
        $this->assertInternalType('string', $result['breadcrumbsConfig']['text']);
        
        $this->assertArrayNotHasKey('emptySphinxConfig', $result);
        
        $this->assertArrayNotHasKey('emptyConfig', $result);
        
        $this->assertArrayHasKey('productsConfig', $result);
        $this->assertArrayHasKey('products', $result['productsConfig']);
        $this->assertArrayHasKey('currency', $result['productsConfig']);
        $this->assertArrayHasKey('view', $result['productsConfig']);
        $this->assertInstanceOf(ProductsCollection::class, $result['productsConfig']['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['productsConfig']['currency']);
        $this->assertInternalType('string', $result['productsConfig']['view']);
        
        $this->assertArrayHasKey('paginationConfig', $result);
        $this->assertArrayHasKey('pagination', $result['paginationConfig']);
        $this->assertArrayHasKey('view', $result['paginationConfig']);
        $this->assertInstanceOf(LightPagination::class, $result['paginationConfig']['pagination']);
        $this->assertInternalType('string', $result['paginationConfig']['view']);
        
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('colors', $result['filtersConfig']);
        $this->assertArrayHasKey('sizes', $result['filtersConfig']);
        $this->assertArrayHasKey('brands', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingFields', $result['filtersConfig']);
        $this->assertArrayHasKey('sortingTypes', $result['filtersConfig']);
        $this->assertArrayHasKey('form', $result['filtersConfig']);
        $this->assertArrayHasKey('view', $result['filtersConfig']);
        $this->assertInternalType('array', $result['filtersConfig']['colors']);
        $this->assertInternalType('array', $result['filtersConfig']['sizes']);
        $this->assertInternalType('array', $result['filtersConfig']['brands']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingFields']);
        $this->assertInternalType('array', $result['filtersConfig']['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['filtersConfig']['form']);
        $this->assertInternalType('string', $result['filtersConfig']['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
