<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminCommentChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCommentChangeRequestHandler
 */
class AdminCommentChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCommentChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCommentChangeRequestHandler::adminCommentDataWidgetConfig
     */
    public function testAdminCommentDataWidgetConfig()
    {
        $commentsModel = new class() extends Model {};
        $commentForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCommentDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $commentsModel, $commentForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertInstanceOf(Model::class, $result['commentsModel']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCommentChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CommentForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCommentChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $oldComment = \Yii::$app->db->createCommand('SELECT * FROM {{comments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldComment);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CommentForm'=>[
                        'id'=>1,
                        'text'=>'New text',
                        'active'=>0
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $newComment = \Yii::$app->db->createCommand('SELECT * FROM {{comments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newComment);
        
        $this->assertEquals($oldComment['id'], $newComment['id']);
        $this->assertNotEquals($oldComment['text'], $newComment['text']);
        $this->assertNotEquals($oldComment['active'], $newComment['active']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
