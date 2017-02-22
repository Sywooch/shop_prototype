<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminCategoriesRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCategoriesRequestHandler
 */
class AdminCategoriesRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminCategoriesRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminCategoriesRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCategoriesRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminCategoriesRequestHandler::adminCreateCategoryWidgetConfig
     */
    public function testadminCreateCategoryWidgetConfig()
    {
        $categoriesForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateCategoryWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCategoriesRequestHandler::adminCreateSubcategoryWidgetConfig
     * если данных нет
     */
    public function testAdminCreateSubcategoryWidgetConfig()
    {
        $subcategoryForm = new class() extends AbstractBaseForm {};
        $categoriesModelArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateSubcategoryWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $subcategoryForm, $categoriesModelArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCategoriesRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminCategoriesWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateCategoryWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateSubcategoryWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminCategoriesWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateCategoryWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateSubcategoryWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
