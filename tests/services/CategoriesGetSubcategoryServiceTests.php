<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CategoriesGetSubcategoryService;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;

/**
 * Тестирует класс CategoriesGetSubcategoryService
 */
class CategoriesGetSubcategoryServiceTests extends TestCase
{
     private static $dbClass;
    
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
    }
    
    /**
     * Тестирует метод CategoriesGetSubcategoryService::handle
     * если не переад $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new CategoriesGetSubcategoryService();
        $service->handle();
    }
    
    /**
     * Тестирует метод CategoriesGetSubcategoryService::handle
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
        
        $service = new CategoriesGetSubcategoryService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
