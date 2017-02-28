<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminCommentDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;

/**
 * Тестирует класс AdminCommentDeleteRequestHandler
 */
class AdminCommentDeleteRequestHandlerTests extends TestCase
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
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCommentDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCommentDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $product = \Yii::$app->db->createCommand('SELECT * FROM {{comments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($product);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'CommentForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $product = \Yii::$app->db->createCommand('SELECT * FROM {{comments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($product);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
