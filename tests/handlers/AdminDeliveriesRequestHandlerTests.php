<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminDeliveriesRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    DeliveriesFixture};
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminDeliveriesRequestHandler
 */
class AdminDeliveriesRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class,
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminDeliveriesRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminDeliveriesRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminDeliveriesRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminDeliveriesRequestHandler::adminCreateDeliveriesWidgetConfig
     */
    public function testAdminCreateDeliveriesWidgetConfig()
    {
        $deliveriesForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateDeliveriesWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $deliveriesForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminDeliveriesRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminDeliveriesWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateDeliveriesWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminDeliveriesWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateDeliveriesWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
