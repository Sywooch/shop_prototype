<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminBrandsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminBrandsRequestHandler
 */
class AdminBrandsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminBrandsRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminBrandsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminBrandsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminBrandsRequestHandler::adminCreateBrandWidgetConfig
     */
    public function testAdminCreateBrandWidgetConfig()
    {
        $brandsFormCreate = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateBrandWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $brandsFormCreate);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminBrandsRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminBrandsWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateBrandWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminBrandsWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateBrandWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
