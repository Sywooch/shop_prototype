<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AdminOrderDetailChangeService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    CurrencyFixture,
    DeliveriesFixture,
    PaymentsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    PurchasesFixture,
    SizesFixture};

/**
 * Тестирует класс AdminOrderDetailChangeService
 */
class AdminOrderDetailChangeServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
                'colors'=>ColorsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'payments'=>PaymentsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminOrderDetailChangeService();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeService::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>null,
                        'name'=>'Name',
                        'surname'=>'Surname',
                        'phone'=>'458-01-11',
                        'address'=>'Address str, 1',
                        'city'=>'City',
                        'country'=>'Country',
                        'postcode'=>'postcode',
                        'quantity'=>2,
                        'id_color'=>'id_color',
                        'id_size'=>45,
                        'id_delivery'=>'id_delivery',
                        'id_payment'=>'id_payment',
                        'status'=>'status',
                    ],
                ];
            }
        };
        
        $service = new AdminOrderDetailChangeService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>1,
                        'name'=>'Name',
                        'surname'=>'Surname',
                        'phone'=>'458-01-11',
                        'address'=>'Address str, 1',
                        'city'=>'City',
                        'country'=>'Country',
                        'postcode'=>'postcode',
                        'quantity'=>2,
                        'id_color'=>2,
                        'id_size'=>1,
                        'id_delivery'=>1,
                        'id_payment'=>2,
                        'status'=>'shipped',
                    ],
                ];
            }
        };
        
        $service = new AdminOrderDetailChangeService();
        $result = $service->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        
        $this->assertNotEmpty($result);
        $this->assertEquals(2, $result['quantity']);
        $this->assertEquals(2, $result['id_color']);
        $this->assertEquals(1, $result['id_size']);
        $this->assertEquals(1, $result['id_delivery']);
        $this->assertEquals(2, $result['id_payment']);
        $this->assertEquals(1, $result['shipped']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
