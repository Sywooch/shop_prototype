<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminBrandDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;

/**
 * Тестирует класс AdminBrandDeleteRequestHandler
 */
class AdminBrandDeleteRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminBrandDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminBrandDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{brands}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($category);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'BrandsForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{brands}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($category);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
