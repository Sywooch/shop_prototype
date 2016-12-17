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
use app\collections\{LightPagination,
    ProductsCollection};
use app\models\CurrencyModel;
use app\forms\FiltersForm;
use app\helpers\HashHelper;

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
     * Тестирует метод ProductsListIndexService::handle
     * если категории пусты, страница === 0, фильтры отсутствуют,
     * выборка не пуста
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [];
        
        $service = new ProductsListIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
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
        
        $this->assertArrayNotHasKey('breadcrumbsConfig', $result);
        
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
     * Тестирует метод ProductsListIndexService::handle
     * если доступны категория и подкатегория, но пусты страница, фильтры,
     * выборка не пуста
     */
    public function testHandleCategories()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $fixtureCategory = self::$dbClass->categories['category_1'];
        $fixtureSubcategory = self::$dbClass->subcategory['subcategory_1'];
        
        $request = [
            \Yii::$app->params['categoryKey']=>$fixtureCategory['seocode'],
            \Yii::$app->params['subcategoryKey']=>$fixtureSubcategory['seocode']
        ];
        
        $service = new ProductsListIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
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
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('category', $result['breadcrumbsConfig']);
        $this->assertArrayHasKey('subcategory', $result['breadcrumbsConfig']);
        
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
     * Тестирует метод ProductsListIndexService::handle
     * если доступны категория, подкатегория и страница, пусты фильтры,
     * выборка не пуста
     */
    public function testHandlePage()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $fixtureCategory = self::$dbClass->categories['category_1'];
        $fixtureSubcategory = self::$dbClass->subcategory['subcategory_1'];
        
        $request = [
            \Yii::$app->params['categoryKey']=>$fixtureCategory['seocode'],
            \Yii::$app->params['subcategoryKey']=>$fixtureSubcategory['seocode'],
            \Yii::$app->params['pagePointer']=>2,
        ];
        
        $service = new ProductsListIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
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
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('category', $result['breadcrumbsConfig']);
        $this->assertArrayHasKey('subcategory', $result['breadcrumbsConfig']);
        
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
     * Тестирует метод ProductsListIndexService::handle
     * если категории пусты, страница === 0, доступны фильтры,
     * выборка не пуста
     */
    public function testHandleFilters()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [];
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set(HashHelper::createFiltersKey(), [
            'colors'=>[1, 2, 3, 4, 5],
            'sizes'=>[1, 2, 3, 4, 5],
            'brands'=>[1, 2, 3, 4, 5],
        ]);
        
        $service = new ProductsListIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
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
        
        $this->assertArrayNotHasKey('breadcrumbsConfig', $result);
        
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
     * Тестирует метод ProductsListIndexService::handle
     * если выборка пуста
     */
    public function testHandleEmpty()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = [];
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set(HashHelper::createFiltersKey(), [
            'colors'=>[12],
            'sizes'=>[12],
            'brands'=>[12],
        ]);
        
        $service = new ProductsListIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('emptyConfig', $result);
        $this->assertArrayHasKey('view', $result['emptyConfig']);
        
        $this->assertArrayNotHasKey('productsConfig', $result);
        
        $this->assertArrayHasKey('paginationConfig', $result);
        $this->assertArrayHasKey('pagination', $result['paginationConfig']);
        $this->assertArrayHasKey('view', $result['paginationConfig']);
        $this->assertInstanceOf(LightPagination::class, $result['paginationConfig']['pagination']);
        $this->assertInternalType('string', $result['paginationConfig']['view']);
        
        $this->assertArrayNotHasKey('breadcrumbsConfig', $result);
        
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
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
