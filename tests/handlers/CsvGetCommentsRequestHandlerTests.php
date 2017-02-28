<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CsvGetCommentsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\models\CommentsModel;

/**
 * Тестирует класс CsvGetCommentsRequestHandler
 */
class CsvGetCommentsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->handler = new CsvGetCommentsRequestHandler();
    }
    
    /**
     * Тестирует свойства CsvGetCommentsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CsvGetCommentsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('file'));
    }
    
    /**
     * Тестирует метод CsvGetCommentsRequestHandler::writeHeaders
     */
    public function testWriteHeaders()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/comments/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeHeaders');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/comments/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetCommentsRequestHandler::writeComment
     */
    public function testWriteProduct()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/comments/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeComment');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, CommentsModel::findOne(1));
        
        $files = glob(\Yii::getAlias('@csvroot/comments/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetCommentsRequestHandler::write
     */
    public function testWrite()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/comments/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'write');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, ['One', 2, 'Two']);
        
        $files = glob(\Yii::getAlias('@csvroot/comments/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetCommentsRequestHandler::cleanCsv
     */
    public function testCleanCsv()
    {
        $reflection = new \ReflectionProperty($this->handler, 'path');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, \Yii::getAlias('@csvroot/comments/test.csv'));
        
        $file = fopen(\Yii::getAlias('@csvroot/comments/test.csv'), 'w');
        fclose($file);
        
        $files = glob(\Yii::getAlias('@csvroot/comments/*.csv'));
        $this->assertNotEmpty($files);
        
        $reflection = new \ReflectionMethod($this->handler, 'cleanCsv');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/comments/*.csv'));
        $this->assertEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetCommentsRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertRegExp('#<a href=".+\.csv">.+\.csv</a>#', $result);
        
        $files = glob(\Yii::getAlias('@csvroot/comments/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    public function tearDown()
    {
        $files = glob(\Yii::getAlias('@csvroot/comments/*'));
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
