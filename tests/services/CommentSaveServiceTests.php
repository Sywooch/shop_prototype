<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CommentSaveService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CommentsFixture,
    EmailsFixture,
    NamesFixture,
    ProductsFixture};
use app\forms\CommentForm;

/**
 * Тестирует класс CommentSaveService
 */
class CommentSaveServiceTests extends TestCase
{
    private static $dbClass;
    
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
    
    /**
     * Тестирует метод CommentSaveService::handle
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
        
        $service = new CommentSaveService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('commentform-text', $result);
    }
    
    /**
     * Тестирует метод CommentSaveService::handle
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
        
        $service = new CommentSaveService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertEquals('<p>Комментарий сохранен и будет доступен после проверки модератором. Спасибо!</p>', trim($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
