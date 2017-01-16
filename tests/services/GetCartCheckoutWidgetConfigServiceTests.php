<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCartCheckoutWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    DeliveriesFixture,
    PaymentsFixture,
    UsersFixture};
use app\models\{CurrencyModel,
    UsersModel};
use app\forms\CustomerInfoForm;

/**
 * Тестирует класс GetCartCheckoutWidgetConfigService
 */
class GetCartCheckoutWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'payments'=>PaymentsFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetCartCheckoutWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCartCheckoutWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('cartCheckoutWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCartCheckoutWidgetConfigService::handle
     * если isGuest is true
     */
    public function testHandleGuest()
    {
        \Yii::$app->user->logout();
        
        $service = new GetCartCheckoutWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('deliveries', $result);
        $this->assertArrayHasKey('payments', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['deliveries']);
        $this->assertInternalType('array', $result['payments']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        
        $this->assertInstanceOf(CustomerInfoForm::class, $result['form']);
        $this->assertEmpty($result['form']->name);
        $this->assertEmpty($result['form']->surname);
        $this->assertEmpty($result['form']->email);
        $this->assertEmpty($result['form']->phone);
        $this->assertEmpty($result['form']->address);
        $this->assertEmpty($result['form']->city);
        $this->assertEmpty($result['form']->country);
        $this->assertEmpty($result['form']->postcode);
        
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод GetCartCheckoutWidgetConfigService::handle
     * если isGuest is false
     */
    public function testHandleNotGuest()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new GetCartCheckoutWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('deliveries', $result);
        $this->assertArrayHasKey('payments', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['deliveries']);
        $this->assertInternalType('array', $result['payments']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        
        $this->assertInstanceOf(CustomerInfoForm::class, $result['form']);
        $this->assertEquals($user->name->name, $result['form']->name);
        $this->assertEquals($user->surname->surname, $result['form']->surname);
        $this->assertEquals($user->email->email, $result['form']->email);
        $this->assertEquals($user->phone->phone, $result['form']->phone);
        $this->assertEquals($user->address->address, $result['form']->address);
        $this->assertEquals($user->city->city, $result['form']->city);
        $this->assertEquals($user->country->country, $result['form']->country);
        $this->assertEquals($user->postcode->postcode, $result['form']->postcode);
        
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
