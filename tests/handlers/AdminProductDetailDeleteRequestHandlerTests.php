<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminProductDetailDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс AdminProductDetailDeleteRequestHandler
 */
class AdminProductDetailDeleteRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminProductDetailDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminProductDetailDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->db->createCommand('UPDATE {{products}} SET [[images]]="" WHERE [[id]]=:id')->bindValue(':id', 1)->execute();
        $product = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($product);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminProductForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $product = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($product);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
