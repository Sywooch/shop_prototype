<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ReceivedOrderEmailService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    CurrencyFixture,
    DeliveriesFixture,
    PaymentsFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture};
use app\helpers\HashHelper;
use app\collections\{PurchasesCollection,
    PurchasesCollectionInterface};
use app\forms\AbstractBaseForm;
use app\models\{CurrencyInterface,
    CurrencyModel};

/**
 * Тестирует класс ReceivedOrderEmailService
 */
class ReceivedOrderEmailServiceTests extends TestCase
{
    private static $dbClass;
    private $service;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'products'=>ProductsFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'payments'=>PaymentsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->service = new ReceivedOrderEmailService();
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::setEmail
     */
    public function testSetEmail()
    {
        $this->service->setEmail('mail@email.com');
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals('mail@email.com', $result);
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::setOrdersCollection
     */
    public function testSetOrdersCollection()
    {
        $this->service->setOrdersCollection(new class() extends PurchasesCollection {});
        
        $reflection = new \ReflectionProperty($this->service, 'ordersCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::setCustomerInfoForm
     */
    public function testSetCustomerInfoForm()
    {
        $this->service->setCustomerInfoForm(new class() extends AbstractBaseForm {});
        
        $reflection = new \ReflectionProperty($this->service, 'customerInfoForm');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::setCurrentCurrencyModel
     */
    public function testSetCurrentCurrencyModel()
    {
        $this->service->setCurrentCurrencyModel(new class() extends CurrencyModel {});
        
        $reflection = new \ReflectionProperty($this->service, 'currentCurrencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertInstanceOf(CurrencyInterface::class, $result);
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::get
     * если пуст ReceivedOrderEmailService::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::get
     * если пуст ReceivedOrderEmailService::ordersCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: ordersCollection
     */
    public function testHandleEmptyOrdersCollection()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $mock);
        
        $this->service->get();
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::get
     * если пуст ReceivedOrderEmailService::customerInfoForm
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: customerInfoForm
     */
    public function testHandleEmptyCustomerInfoForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $mock);
        
        $reflection = new \ReflectionProperty($this->service, 'ordersCollection');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $mock);
        
        $this->service->get();
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::get
     * если пуст ReceivedOrderEmailService::currentCurrencyModel
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currentCurrencyModel
     */
    public function testHandleEmptyCurrentCurrencyModel()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $mock);
        
        $reflection = new \ReflectionProperty($this->service, 'ordersCollection');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $mock);
        
        $reflection = new \ReflectionProperty($this->service, 'customerInfoForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $mock);
        
        $this->service->get();
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::get
     */
    public function testGet()
    {
        $items = [
            new class() {
                public $product;
                public $color;
                public $size;
                public $quantity = 2;
                public $id_color = 1;
                public $id_size = 2;
                public $id_product = 1;
                public $price = 268.78;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'seocode';
                        public $name = 'name';
                        public $short_description = 'short_description';
                    };
                    $this->color = new class() {
                        public $color = 'color';
                    };
                    $this->size = new class() {
                        public $size = 'size';
                    };
                }
            },
        ];
        
        $ordersCollection = new class() extends PurchasesCollection {
            public $items = [];
        };
        $reflection = new \ReflectionProperty($ordersCollection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($ordersCollection, $items);
        
        $customerInfoForm = new class() extends AbstractBaseForm {
            public $name = 'John';
            public $surname = 'Doe';
            public $email = 'jahn@com.com';
            public $phone = '+387968965';
            public $address = 'ул. Черноозерная; 1';
            public $city = 'Каркоза';
            public $country = 'Гиады';
            public $postcode = '08789';
            public $id_delivery = 1;
            public $id_payment = 1;
        };
        
        $currentCurrencyModel = new class() extends CurrencyModel {
            public $exchange_rate = 1.034;
            public $code = 'MONEY';
            
        };
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        $this->assertEmpty($files);
        
        $reflection = new \ReflectionProperty($this->service, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, 'mail@mail.com');
        
        $reflection = new \ReflectionProperty($this->service, 'ordersCollection');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $ordersCollection);
        
        $reflection = new \ReflectionProperty($this->service, 'customerInfoForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $customerInfoForm);
        
        $reflection = new \ReflectionProperty($this->service, 'currentCurrencyModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $currentCurrencyModel);
        
        $this->service->get();
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertNotEmpty($files);
    }
    
    public static function tearDownAfterClass()
    {
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        
        if (file_exists($saveDir) && is_dir($saveDir)) {
            $files = glob($saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
        
        self::$dbClass->unloadFixtures();
    }
}
