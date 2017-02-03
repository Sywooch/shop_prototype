<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartCleanService;
use app\helpers\HashHelper;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};

/**
 * Тестирует класс CartCleanService
 */
class CartCleanServiceTests extends TestCase
{
     private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод CartCleanService::handle
     * если не переад $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new CartCleanService();
        $service->handle();
    }
    
    /**
     * Тестирует метод CartCleanService::handle
     */
    public function testHandle()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(HashHelper::createCartKey(), [['id_product'=>1, 'quantity'=>1, 'id_color'=>1, 'id_size'=>1, 'price'=>123.87]]);
        $session->set(HashHelper::createCartCustomerKey(), [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'delivery'=>1,
            'payment'=>1,
        ]);
        
        $this->assertTrue($session->has(HashHelper::createCartKey()));
        $this->assertTrue($session->has(HashHelper::createCartCustomerKey()));
        
        $request = new class() {
            public $isAjax = true;
        };
        
        $service = new CartCleanService();
        $result = $service->handle($request);
        
        $this->assertFalse($session->has(HashHelper::createCartKey()));
        $this->assertFalse($session->has(HashHelper::createCartCustomerKey()));
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertRegExp('#<p>Товаров в корзине: 0, Общая стоимость: 0,00 UAH</p>#', $result);
        
        $session->remove(HashHelper::createCartKey());
        $session->remove(HashHelper::createCartCustomerKey());
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
