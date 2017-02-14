<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CategoriesGetSubcategoryRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;

/**
 * Тестирует класс CategoriesGetSubcategoryRequestHandler
 */
class CategoriesGetSubcategoryRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new CategoriesGetSubcategoryRequestHandler();
    }
    
    /**
     * Тестирует метод CategoriesGetSubcategoryRequestHandler::subcategoryOptionWidgetConfig
     */
    public function testSubcategoryOptionWidgetConfig()
    {
        $subcategoryArray = [];
        
        $reflection = new \ReflectionMethod($this->handler, 'subcategoryOptionWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $subcategoryArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('subcategoryArray', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['subcategoryArray']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод CategoriesGetSubcategoryRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return 1;
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
