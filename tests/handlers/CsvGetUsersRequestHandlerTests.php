<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CsvGetUsersRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс CsvGetUsersRequestHandler
 */
class CsvGetUsersRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->handler = new CsvGetUsersRequestHandler();
    }
    
    /**
     * Тестирует свойства CsvGetUsersRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CsvGetUsersRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('file'));
    }
    
    /**
     * Тестирует метод CsvGetUsersRequestHandler::writeHeaders
     */
    public function testWriteHeaders()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/users/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeHeaders');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/users/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetUsersRequestHandler::writeUser
     */
    public function testWriteUser()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/users/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'writeUser');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, UsersModel::findOne(1));
        
        $files = glob(\Yii::getAlias('@csvroot/users/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetUsersRequestHandler::write
     */
    public function testWrite()
    {
        $reflection = new \ReflectionProperty($this->handler, 'file');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, fopen(\Yii::getAlias('@csvroot/users/test.csv'), 'w'));
        
        $reflection = new \ReflectionMethod($this->handler, 'write');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, ['One', 2, 'Two']);
        
        $files = glob(\Yii::getAlias('@csvroot/users/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetUsersRequestHandler::cleanCsv
     */
    public function testCleanCsv()
    {
        $reflection = new \ReflectionProperty($this->handler, 'path');
        $reflection->setAccessible(true);
        $reflection->setValue($this->handler, \Yii::getAlias('@csvroot/users/test.csv'));
        
        $file = fopen(\Yii::getAlias('@csvroot/users/test.csv'), 'w');
        fclose($file);
        
        $files = glob(\Yii::getAlias('@csvroot/users/*.csv'));
        $this->assertNotEmpty($files);
        
        $reflection = new \ReflectionMethod($this->handler, 'cleanCsv');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $files = glob(\Yii::getAlias('@csvroot/users/*.csv'));
        $this->assertEmpty($files);
    }
    
    /**
     * Тестирует метод CsvGetUsersRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertRegExp('#<a href=".+\.csv">.+\.csv</a>#', $result);
        
        $files = glob(\Yii::getAlias('@csvroot/users/*.csv'));
        $this->assertNotEmpty($files);
    }
    
    public function tearDown()
    {
        $files = glob(\Yii::getAlias('@csvroot/users/*'));
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
