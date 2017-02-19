<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminAddProductRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\ProductsModel;
use app\forms\{AbstractBaseForm,
    AdminProductForm};

/**
 * Тестирует класс AdminAddProductRequestHandler
 */
class AdminAddProductRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'brands'=>BrandsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminAddProductRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminAddProductRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminAddProductRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminAddProductRequestHandler::adminAddProductFormWidgetConfig
     */
    public function testAminAddProductFormWidgetConfig()
    {
        $categoriesArray = [new class() {
            public $id = 1;
            public $name = 'category';
        }];
        
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
        
        $adminProductForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminAddProductFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesArray, $colorsArray, $sizesArray, $brandsArray, $adminProductForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminAddProductRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminAddProductFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminAddProductFormWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
