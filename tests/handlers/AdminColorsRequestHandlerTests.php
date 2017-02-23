<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminColorsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminColorsRequestHandler
 */
class AdminColorsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminColorsRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminColorsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminColorsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminColorsRequestHandler::adminCreateColorWidgetConfig
     */
    public function testAdminCreateColorWidgetConfig()
    {
        $colorsFormCreate = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateColorWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $colorsFormCreate);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminColorsRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminColorsWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateColorWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminColorsWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateColorWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
