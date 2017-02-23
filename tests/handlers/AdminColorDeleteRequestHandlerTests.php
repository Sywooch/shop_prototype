<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminColorDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;

/**
 * Тестирует класс AdminColorDeleteRequestHandler
 */
class AdminColorDeleteRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminColorDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminColorDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{colors}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($category);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'ColorsForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{colors}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($category);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
