<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminSizesRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\SizesFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminSizesRequestHandler
 */
class AdminSizesRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminSizesRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminSizesRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminSizesRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminSizesRequestHandler::adminCreateSizeWidgetConfig
     */
    public function testAdminCreateSizeWidgetConfig()
    {
        $sizesFormCreate = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateSizeWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $sizesFormCreate);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminSizesRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminSizesWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateSizeWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminSizesWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateSizeWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
