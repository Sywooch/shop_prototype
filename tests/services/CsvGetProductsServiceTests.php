<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CsvGetProductsService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\models\ProductsModel;

/**
 * Тестирует класс CsvGetProductsService
 */
class CsvGetProductsServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CsvGetProductsService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CsvGetProductsService::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('file'));
    }
    
    /**
     * Тестирует метод CsvGetProductsService::writeHeaders
     */
    public function testWriteHeaders()
    {
        $service = new CsvGetProductsService();
        
        $reflection = new \ReflectionProperty($service, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($service, fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($service, 'writeHeaders');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsService::writeOrder
     */
    public function testWriteOrder()
    {
        $service = new CsvGetProductsService();
        
        $reflection = new \ReflectionProperty($service, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($service, fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($service, 'writeOrder');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, ProductsModel::findOne(1));
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsService::write
     */
    public function testWrite()
    {
        $service = new CsvGetProductsService();
        
        $reflection = new \ReflectionProperty($service, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($service, fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($service, 'write');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, ['One', 2, 'Two']);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsService::cleanCsv
     */
    public function testCleanCsv()
    {
        $service = new CsvGetProductsService();
        
        $reflection = new \ReflectionProperty($service, 'path');
        $reflection->setAccessible(true);
        $reflection->setValue($service, \Yii::getAlias('@csvroot/products/test.csv'));
        
        $file = fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w');
        fclose($file);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
        
        $reflection = new \ReflectionMethod($service, 'cleanCsv');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
        };
        
        $service = new CsvGetProductsService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertRegExp('#<a href=".+\.csv">.+\.csv</a>#', $result);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    public function tearDown()
    {
        $files = glob(\Yii::getAlias('@csvroot/products/*'));
        if (!empty($files)) {
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
