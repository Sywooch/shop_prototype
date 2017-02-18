<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminProductDetailChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture};
use app\models\{CurrencyInterface,
    CurrencyModel,
    ProductsModel};
use app\forms\{AbstractBaseForm,
    AdminProductForm};

/**
 * Тестирует класс AdminProductDetailChangeRequestHandler
 */
class AdminProductDetailChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'products'=>ProductsFixture::class,
                'products_size'=>ProductsSizesFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminProductDetailChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminProductDetailChangeRequestHandler::adminProductDataWidgetConfig
     */
    public function testAdminProductDataWidgetConfig()
    {
        $productsModel = new class() extends ProductsModel {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $adminProductForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminProductDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsModel, $currentCurrencyModel, $adminProductForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertInstanceOf(Model::class, $result['productsModel']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductDetailChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $oldProduct = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldProduct);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductForm'=>[
                        'id'=>1,
                        'code'=>'NEWCODE',
                        'name'=>'New Name',
                        'short_description'=>'New short escription',
                        'description'=>'New description',
                        'price'=>46897.88,
                        'images'=>'test',
                        'id_category'=>2,
                        'id_subcategory'=>2,
                        'id_colors'=>[1, 2, 3],
                        'id_sizes'=>[1, 2],
                        'id_brand'=>2,
                        'active'=>false,
                        'total_products'=>204,
                        'seocode'=>'new-product',
                        'views'=>304
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $newProduct = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newProduct);
        
        $this->assertEquals($oldProduct['id'], $newProduct['id']);
        $this->assertNotEquals($oldProduct['code'], $newProduct['code']);
        $this->assertNotEquals($oldProduct['name'], $newProduct['name']);
        $this->assertNotEquals($oldProduct['short_description'], $newProduct['short_description']);
        $this->assertNotEquals($oldProduct['description'], $newProduct['description']);
        $this->assertNotEquals($oldProduct['price'], $newProduct['price']);
        $this->assertNotEquals($oldProduct['active'], $newProduct['active']);
        $this->assertNotEquals($oldProduct['total_products'], $newProduct['total_products']);
        $this->assertNotEquals($oldProduct['seocode'], $newProduct['seocode']);
        $this->assertNotEquals($oldProduct['views'], $newProduct['views']);
        $this->assertNotEquals($oldProduct['id_category'], $newProduct['id_category']);
        $this->assertNotEquals($oldProduct['id_subcategory'], $newProduct['id_subcategory']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
