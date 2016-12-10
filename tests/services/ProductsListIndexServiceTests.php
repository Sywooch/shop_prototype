<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\{ProductsListIndexService,
    ServiceInterface};
use app\models\{CategoriesModel,
    CurrencyModel,
    SubcategoryModel};
use app\collections\CollectionInterface;
use app\widgets\{PaginationWidget,
    PriceWidget,
    ThumbnailsWidget};
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SubcategoryFixture};
use app\forms\FiltersForm;
use app\controllers\ProductsListController;

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
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'colors'=>ProductsColorsFixture::class,
                'sizes'=>ProductsSizesFixture::class,
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ProductsListIndexService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $category = self::$dbClass->categories['category_1'];
        $subcategory = self::$dbClass->subcategory['subcategory_1'];
        
        $request = [\Yii::$app->params['categoryKey']=>$category['seocode'], \Yii::$app->params['subcategoryKey']=>$subcategory['seocode']];
        
        $service = new ProductsListIndexService();
        
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('productsConfig', $result);
        $this->assertArrayHasKey('productsCollection', $result['productsConfig']);
        $this->assertArrayHasKey('priceWidget', $result['productsConfig']);
        $this->assertArrayHasKey('thumbnailsWidget', $result['productsConfig']);
        $this->assertArrayHasKey('paginationWidget', $result['productsConfig']);
        $this->assertArrayHasKey('view', $result['productsConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['productsConfig']['productsCollection']);
        $this->assertInstanceOf(PriceWidget::class, $result['productsConfig']['priceWidget']);
        $this->assertInstanceOf(ThumbnailsWidget::class, $result['productsConfig']['thumbnailsWidget']);
        $this->assertInstanceOf(PaginationWidget::class, $result['productsConfig']['paginationWidget']);
        $this->assertInternalType('string', $result['productsConfig']['view']);
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('category', $result['breadcrumbsConfig']);
        $this->assertArrayHasKey('subcategory', $result['breadcrumbsConfig']);
        $this->assertInstanceOf(CategoriesModel::class, $result['breadcrumbsConfig']['category']);
        $this->assertInstanceOf(SubcategoryModel::class, $result['breadcrumbsConfig']['subcategory']);
        
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('colorsCollection', $result['filtersConfig']);
        $this->assertArrayHasKey('sizesCollection', $result['filtersConfig']);
        $this->assertArrayHasKey('brandsCollection', $result['filtersConfig']);
        $this->assertArrayHasKey('form', $result['filtersConfig']);
        $this->assertArrayHasKey('view', $result['filtersConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['filtersConfig']['colorsCollection']);
        $this->assertInstanceOf(CollectionInterface::class, $result['filtersConfig']['sizesCollection']);
        $this->assertInstanceOf(CollectionInterface::class, $result['filtersConfig']['brandsCollection']);
        $this->assertInstanceOf(FiltersForm::class, $result['filtersConfig']['form']);
        $this->assertInternalType('string', $result['filtersConfig']['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
