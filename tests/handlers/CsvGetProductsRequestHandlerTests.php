<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CsvGetProductsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\models\ProductsModel;

/**
 * Тестирует класс CsvGetProductsRequestHandler
 */
class CsvGetProductsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
    
    public function setUp()
    {
        $this->handler = new CsvGetProductsRequestHandler();
    }
    
    /**
     * Тестирует свойства CsvGetProductsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CsvGetProductsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('file'));
    }
    
    /**
     * Тестирует метод CsvGetProductsRequestHandler::writeHeaders
     */
    public function testWriteHeaders()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeHeaders');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsRequestHandler::writeProduct
     */
    public function testWriteProduct()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeProduct');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, ProductsModel::findOne(1));
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsRequestHandler::write
     */
    public function testWrite()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'write');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, ['One', 2, 'Two']);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsRequestHandler::cleanCsv
     */
    public function testCleanCsv()
    {
        $reflection = new \ReflectionProperty($this->handler, 'path');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, \Yii::getAlias('@csvroot/products/test.csv'));
        
        $file = fopen(\Yii::getAlias('@csvroot/products/test.csv'), 'w');
        fclose($file);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertNotEmpty($files);
        
        $reflection = new \ReflectionMethod($this->handler, 'cleanCsv');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/products/*.csv'));
        $this->assertEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetProductsRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
        };
        
        $result = $this->handler->handle($request);
        
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
