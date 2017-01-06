<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CurrencySetService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use yii\web\Request;

/**
 * Тестирует класс CurrencySetService
 */
class CurrencySetServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CurrencySetService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencySetService::class);
        
        $this->assertTrue($reflection->hasProperty('currencyWidgetArray'));
        $this->assertTrue($reflection->hasProperty('form'));
    }
    
    /**
     * Тестирует метод CurrencySetService::getCurrencyWidgetArray
     */
    public function testGetCurrencyWidgetArray()
    {
        $service = new CurrencySetService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::SET]));
        
        $reflection = new \ReflectionMethod($service, 'getCurrencyWidgetArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод CurrencySetService::setCurrency
     */
    public function testSetCurrency()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->remove(HashHelper::createCurrencyKey());
        
        $service = new CurrencySetService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new ChangeCurrencyForm([
            'scenario'=>ChangeCurrencyForm::SET, 
            'id'=>self::$dbClass->currency['currency_2'],
        ]));
        
        $this->assertFalse($session->has(HashHelper::createCurrencyKey()));
        
        $reflection = new \ReflectionMethod($service, 'setCurrency');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertTrue($result);
        
        $this->assertTrue($session->has(HashHelper::createCurrencyKey()));
        $result = $session->get(HashHelper::createCurrencyKey());
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        
        $session->remove(HashHelper::createCurrencyKey());
        $session->close();
    }
    
     /**
     * Тестирует метод CurrencySetService::handle
     * если запрос GET
     */
    public function testHandleGet()
    {
        $request = new class() extends Request {
            public $isAjax = false;
        };
        
        $service = new CurrencySetService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
    }
    
    /**
     * Тестирует метод CurrencySetService::handle
     * если запрос AJAX с ошибками
     */
    public function testHandleAjaxErrors()
    {
        $request = new class() extends Request {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ChangeCurrencyForm'=>[
                        'id'=>null,
                    ]
                ];
            }
        };
        
        $service = new CurrencySetService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('changecurrencyform-id', $result);
    }
    
    /**
     * Тестирует метод CurrencySetService::handle
     */
    public function testHandleAjax()
    {
        $request = new class() extends Request {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ChangeCurrencyForm'=>[
                        'id'=>2,
                    ]
                ];
            }
        };
        
        $service = new CurrencySetService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('cartInfo', $result);
        $this->assertInternalType('string', $result['cartInfo']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
