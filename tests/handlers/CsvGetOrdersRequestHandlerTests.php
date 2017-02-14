<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CsvGetOrdersRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\{CurrencyModel,
    PurchasesModel};

/**
 * Тестирует класс CsvGetOrdersRequestHandler
 */
class CsvGetOrdersRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
    
    public function setUp()
    {
        $this->handler = new CsvGetOrdersRequestHandler();
    }
    
    /**
     * Тестирует свойства CsvGetOrdersRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CsvGetOrdersRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('file'));
    }
    
    /**
     * Тестирует метод CsvGetOrdersRequestHandler::writeHeaders
     */
    public function testWriteHeaders()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeHeaders');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersRequestHandler::writeOrder
     */
    public function testWriteOrder()
    {
        $currentCurrencyModel = new class() extends CurrencyModel {
            public $code = 'MONEY';
            public $exchange_rate = 2.16;
        };
        
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeOrder');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, PurchasesModel::findOne(1), $currentCurrencyModel);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersRequestHandler::write
     */
    public function testWrite()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'write');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, ['One', 2, 'Two']);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersRequestHandler::cleanCsv
     */
    public function testCleanCsv()
    {
        $reflection = new \ReflectionProperty($this->handler, 'path');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, \Yii::getAlias('@csvroot/orders/test.csv'));
        
        $file = fopen(\Yii::getAlias('@csvroot/orders/test.csv'), 'w');
        fclose($file);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertNotEmpty($files);
        
        $reflection = new \ReflectionMethod($this->handler, 'cleanCsv');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/orders/*.csv'));
        $this->assertEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetOrdersRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
        };
        
        $result = $this->handler->handle($request);
        
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
