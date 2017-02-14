<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CommentSaveRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CommentsFixture,
    EmailsFixture,
    NamesFixture,
    ProductsFixture};
use app\forms\CommentForm;

/**
 * Тестирует класс CommentSaveRequestHandler
 */
class CommentSaveRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
                'names'=>NamesFixture::class,
                'emails'=>EmailsFixture::class,
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->handler = new CommentSaveRequestHandler();
    }
    
    /**
     * Тестирует метод CommentSaveRequestHandler::handle
     * если запрос AJAX с ошибками
     */
    public function testHandleAjaxErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public $text;
            public $name;
            public $email;
            public $id_product;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CommentForm'=>[
                        'text'=>$this->text,
                        'name'=>$this->name,
                        'email'=>$this->email,
                        'id_product'=>$this->id_product,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'name');
        $reflection->setValue($request, self::$dbClass->names['name_1']['name']);
        $reflection = new \ReflectionProperty($request, 'id_product');
        $reflection->setValue($request, self::$dbClass->products['product_1']['id']);
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('commentform-text', $result);
    }
    
    /**
     * Тестирует метод CommentSaveRequestHandler::handle
     * если запрос AJAX
     */
    public function testHandleAjax()
    {
        $request = new class() {
            public $isAjax = true;
            public $text = 'Some text';
            public $name;
            public $email;
            public $id_product;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CommentForm'=>[
                        'text'=>$this->text,
                        'name'=>$this->name,
                        'email'=>$this->email,
                        'id_product'=>$this->id_product,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'name');
        $reflection->setValue($request, self::$dbClass->names['name_1']['name']);
        $reflection = new \ReflectionProperty($request, 'id_product');
        $reflection->setValue($request, self::$dbClass->products['product_1']['id']);
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertEquals('<p>Комментарий сохранен и будет доступен после проверки модератором. Спасибо!</p>', trim($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
