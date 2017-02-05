<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use yii\db\ActiveQuery;
use app\services\AdminProductsCsvArrayService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\helpers\HashHelper;

/**
 * Тестирует класс AdminProductsCsvArrayService
 */
class AdminProductsCsvArrayServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminProductsCsvArrayService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsCsvArrayService::class);
        
        $this->assertTrue($reflection->hasProperty('productsQuery'));
    }
    
    /**
     * Тестирует метод AdminProductsCsvArrayService::handle
     * filters === false
     */
    public function testHandleFiltersFalse()
    {
        $service = new AdminProductsCsvArrayService();
        $result = $service->handle();
        
        $this->assertInstanceOf(ActiveQuery::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsCsvArrayService::handle
     * filters === true
     */
    public function testHandleFilters()
    {
        $key = HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'category'=>1
        ]);

        $service = new AdminProductsCsvArrayService();
        $result = $service->handle();

        $this->assertInstanceOf(ActiveQuery::class, $result);

        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
