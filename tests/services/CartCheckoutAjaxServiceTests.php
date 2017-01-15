<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartCheckoutAjaxService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{AddressFixture,
    CitiesFixture,
    ColorsFixture,
    CountriesFixture,
    CurrencyFixture,
    DeliveriesFixture,
    EmailsFixture,
    NamesFixture,
    PhonesFixture,
    PaymentsFixture,
    PostcodesFixture,
    ProductsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    PurchasesFixture,
    SizesFixture,
    SurnamesFixture};
use app\helpers\HashHelper;

/**
 * Тестирует класс CartCheckoutAjaxService
 */
class CartCheckoutAjaxServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'names'=>NamesFixture::class,
                'surnames'=>SurnamesFixture::class,
                'emails'=>EmailsFixture::class,
                'phones'=>PhonesFixture::class,
                'address'=>AddressFixture::class,
                'cities'=>CitiesFixture::class,
                'countries'=>CountriesFixture::class,
                'postcodes'=>PostcodesFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'payments'=>PaymentsFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'purchases'=>PurchasesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод CartCheckoutAjaxService::handle
     * если запрос с ошибками
     */
    public function testHandleErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CustomerInfoForm'=>[
                        'name'=>null,
                        'surname'=>'Doe',
                        'email'=>'jahn@com.com',
                        'phone'=>'+387968965',
                        'address'=>'ул. Черноозерная, 1',
                        'city'=>'Каркоза',
                        'country'=>'Гиады',
                        'postcode'=>'08789',
                        'id_delivery'=>1,
                        'id_payment'=>1,
                    ]
                ];
            }
        };
        
        $service = new CartCheckoutAjaxService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('customerinfoform-name', $result);
    }
    
    /**
     * Тестирует метод CartCheckoutAjaxService::handle
     * если данные уже в СУБД
     */
    public function testHandleExists()
    {
        $session = \Yii::$app->session;
        $session->open();
        
        $session->set(HashHelper::createCartKey(), [
            ['quantity'=>2, 'id_color'=>2, 'id_size'=>2, 'id_product'=>1, 'price'=>268.78],
            ['quantity'=>1, 'id_color'=>1, 'id_size'=>2, 'id_product'=>2, 'price'=>1987.00]
        ]);
        
        $this->assertCount(2, \Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll());
        
        $request = new class() {
            public $isAjax = true;
            public $name;
            public $surname;
            public $email;
            public $phone;
            public $address;
            public $city;
            public $country;
            public $postcode;
            public $id_delivery;
            public $id_payment;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CustomerInfoForm'=>[
                        'name'=>$this->name,
                        'surname'=>$this->surname,
                        'email'=>$this->email,
                        'phone'=>$this->phone,
                        'address'=>$this->address,
                        'city'=>$this->city,
                        'country'=>$this->country,
                        'postcode'=>$this->postcode,
                        'id_delivery'=>$this->id_delivery,
                        'id_payment'=>$this->id_payment,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'name');
        $reflection->setValue($request, self::$dbClass->names['name_1']['name']);
        $reflection = new \ReflectionProperty($request, 'surname');
        $reflection->setValue($request, self::$dbClass->surnames['surname_1']['surname']);
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        $reflection = new \ReflectionProperty($request, 'phone');
        $reflection->setValue($request, self::$dbClass->phones['phone_1']['phone']);
        $reflection = new \ReflectionProperty($request, 'address');
        $reflection->setValue($request, self::$dbClass->address['address_1']['address']);
        $reflection = new \ReflectionProperty($request, 'city');
        $reflection->setValue($request, self::$dbClass->cities['city_1']['city']);
        $reflection = new \ReflectionProperty($request, 'country');
        $reflection->setValue($request, self::$dbClass->countries['country_1']['country']);
        $reflection = new \ReflectionProperty($request, 'postcode');
        $reflection->setValue($request, self::$dbClass->postcodes['postcode_1']['postcode']);
        $reflection = new \ReflectionProperty($request, 'id_delivery');
        $reflection->setValue($request, self::$dbClass->deliveries['delivery_1']['id']);
        $reflection = new \ReflectionProperty($request, 'id_payment');
        $reflection->setValue($request, self::$dbClass->payments['payment_1']['id']);
        
        
        $service = new CartCheckoutAjaxService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        /*$this->assertRegExp('#<p>Товаров в корзине: 2, Общая стоимость: 537,56 UAH</p>#', $result);
        $this->assertRegExp('#<p><a href=".+">В корзину</a></p>#', $result);
        $this->assertRegExp('#<form id="clean-cart-form"#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить корзину">#', $result);*/
        
        $this->assertCount(4, \Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll());
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertNotEmpty($files);
        
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        
        if (file_exists($saveDir) && is_dir($saveDir)) {
            $files = glob($saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
