<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CsvGetOrdersService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\PurchasesModel;

/**
 * Тестирует класс CsvGetOrdersService
 */
class CsvGetOrdersServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CsvGetOrdersService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CsvGetOrdersService::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('file'));
    }
    
    /**
     * Тестирует метод CsvGetOrdersService::writeHeaders
     */
    public function testWriteHeaders()
    {
        $service = new CsvGetOrdersService();
        
        $reflection = new \ReflectionProperty($service, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($service, fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($service, 'writeHeaders');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersService::writeOrder
     */
    public function testWriteOrder()
    {
        $service = new CsvGetOrdersService();
        
        $reflection = new \ReflectionProperty($service, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($service, fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($service, 'writeOrder');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, PurchasesModel::findOne(1));
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersService::write
     */
    public function testWrite()
    {
        $service = new CsvGetOrdersService();
        
        $reflection = new \ReflectionProperty($service, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($service, fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($service, 'write');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, ['One', 2, 'Two']);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersService::cleanCsv
     */
    public function testCleanCsv()
    {
        $service = new CsvGetOrdersService();
        
        $reflection = new \ReflectionProperty($service, 'path');
        $reflection->setAccessible(true);
        $reflection->setValue($service, \Yii::getAlias('@csvroot/orders/test.csv'));
        
        $file = fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w');
        fclose($file);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
        
        $reflection = new \ReflectionMethod($service, 'cleanCsv');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
        };
        
        $service = new CsvGetOrdersService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertRegExp('#<a href=".+\.csv">.+\.csv</a>#', $result);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    public function tearDown()
    {
        $files = glob(\Yii::getAlias('@csvroot/orders/*'));
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
