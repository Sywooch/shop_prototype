<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmailReceivedOrderWidgetConfigService;
use app\collections\PurchasesCollection;
use app\forms\CustomerInfoForm;
use app\models\CurrencyModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\helpers\HashHelper;

/**
 * Тестирует класс GetEmailReceivedOrderWidgetConfigService
 */
class GetEmailReceivedOrderWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует класс GetEmailReceivedOrderWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmailReceivedOrderWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emailReceivedOrderWidgetArray'));
    }
    
    /**
     * Тестирует метод GetEmailReceivedOrderWidgetConfigService::handle
     */
    public function testHandle()
    {
        $key = HashHelper::createCartCustomerKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
        ]);
        
        $service = new GetEmailReceivedOrderWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollection::class, $result['purchases']);
        $this->assertInstanceOf(CustomerInfoForm::class, $result['form']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
