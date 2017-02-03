<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetSubcategoryOptionWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;

/**
 * Тестирует класс GetSubcategoryOptionWidgetConfigService
 */
class GetSubcategoryOptionWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetSubcategoryOptionWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetSubcategoryOptionWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('subcategoryOptionWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetSubcategoryOptionWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name=null, $defaultValue=null)
            {
                return 1;
            }
        };
        
        $service = new GetSubcategoryOptionWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('subcategoryArray', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['subcategoryArray']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
