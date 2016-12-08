<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ProductsListSearchService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс ProductsListSearchService
 */
class ProductsListSearchServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
    * Тестирует метод ProductsListSearchService::handle
    * если данных для поисковой фразы не существует
    */
    public function testHandle()
    {
        $service = new ProductsListSearchService();
        $result = $service->handle(['search'=>'adidas']);
        
        $this->assertInternalType('array', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
