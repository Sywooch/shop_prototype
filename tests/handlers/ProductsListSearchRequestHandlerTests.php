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
use app\forms\FiltersForm;
use app\controllers\ProductsListController;
use app\filters\ProductsFilters;
use app\helpers\HashHelper;

/**
 * Тестирует класс ProductsListSearchRequestHandler
 */
class ProductsListSearchRequestHandlerTests extends TestCase
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
        $handler = new ProductsListSearchRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'emptySphinxWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::filtersWidgetConfig
     */
    public function testFiltersWidgetConfig()
    {
        $sphinxArray = [1, 2, 3, 4, 5];
        $filtersModel = new class() extends ProductsFilters {};
        
        $handler = new ProductsListSearchRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'filtersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $sphinxArray, $filtersModel);
        
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
        $this->assertInstanceOf(FiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListSearchRequestHandler::searchBreadcrumbsWidgetConfig
     */
    public function testSearchBreadcrumbsWidgetConfig()
    {
        $handler = new ProductsListSearchRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'searchBreadcrumbsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, 'coats');
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('text', $result);
        $this->assertInternalType('string', $result['text']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
