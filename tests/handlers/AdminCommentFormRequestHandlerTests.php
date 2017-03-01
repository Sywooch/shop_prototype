<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminCommentFormRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCommentFormRequestHandler
 */
class AdminCommentFormRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCommentFormRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminCommentFormRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentFormRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminCommentFormRequestHandler::adminCommentFormWidgetConfig
     */
    public function testAdminCommentFormWidgetConfig()
    {
        $commentsModel = new class() extends Model {};
        $activeStatusesArray = [new class() {}];
        $commentForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCommentFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $commentsModel, $activeStatusesArray, $commentForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('comment', $result);
        $this->assertArrayHasKey('activeStatuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(Model::class, $result['comment']);
        $this->assertInternalType('array', $result['activeStatuses']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCommentFormRequestHandler::handle
     * если пуста форма
     * @expectedException ErrorException
     */
    public function testHandleEmptyForm()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $reqult = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminCommentFormRequestHandler::handle
     */
    public function testHandle()
    {
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
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
