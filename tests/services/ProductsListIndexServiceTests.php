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
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
