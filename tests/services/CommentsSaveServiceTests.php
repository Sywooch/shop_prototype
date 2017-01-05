<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CommentsSaveService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CommentsFixture,
    EmailsFixture,
    ProductsFixture};
use yii\web\Request;
use app\forms\CommentForm;

/**
 * Тестирует класс CommentsSaveService
 */
class CommentsSaveServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'comments'=>CommentsFixture::class,
                'emails'=>EmailsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CommentsSaveService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsSaveService::class);
        
        $this->assertTrue($reflection->hasProperty('commentsWidgetArray'));
        $this->assertTrue($reflection->hasProperty('form'));
    }
    
    /**
     * Тестирует метод CommentsSaveService::getCommentsWidgetArray
     * если не найден товар по запросу
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testGetCommentsWidgetArrayEmptyProduct()
    {
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'nothing';
            }
        };
        
        $service = new CommentsSaveService();
        
        $reflection = new \ReflectionMethod($service, 'getCommentsWidgetArray');
        $reflection->setAccessible(true);
        $reflection->invoke($service, $request);
    }
    
    /**
     * Тестирует метод CommentsSaveService::getCommentsWidgetArray
     * если комментарии не найдены
     */
    public function testGetCommentsWidgetArrayEmptyComments()
    {
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $result = $reflection->setValue($request, self::$dbClass->products['product_3']['seocode']);
        
        $service = new CommentsSaveService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new CommentForm(['scenario'=>CommentForm::SAVE]));
        
        $reflection = new \ReflectionMethod($service, 'getCommentsWidgetArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertArrayNotHasKey('comments', $result);
        $this->assertInstanceOf(CommentForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод CommentsSaveService::getCommentsWidgetArray
     */
    public function testGetCommentsWidgetArray()
    {
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new CommentsSaveService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new CommentForm(['scenario'=>CommentForm::SAVE]));
        
        $reflection = new \ReflectionMethod($service, 'getCommentsWidgetArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('comments', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['comments']);
        $this->assertInstanceOf(CommentForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод CommentsSaveService::getCommentSaveInfo
     */
    public function testGetCommentSaveInfo()
    {
        $service = new CommentsSaveService();
        
        $reflection = new \ReflectionMethod($service, 'getCommentSaveInfo');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод CommentsSaveService::handle
     * если запрос GET
     */
    public function testHandleGet()
    {
        $request = new class() extends Request {
            public $seocode;
            public $isAjax = false;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new CommentsSaveService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new CommentForm(['scenario'=>CommentForm::SAVE]));
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('comments', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
    }
    
    /**
     * Тестирует метод CommentsSaveService::handle
     * если запрос AJAX с ошибками
     */
    public function testHandleAjaxErrors()
    {
        $request = new class() extends Request {
            public $seocode;
            public $isAjax = true;
            public $text;
            public $name = 'John';
            public $email;
            public $id_product;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CommentForm'=>[
                        'text'=>$this->text,
                        'name'=>$this->name,
                        'email'=>$this->email,
                        'email'=>$this->email,
                        'id_product'=>$this->id_product,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'id_product');
        $reflection->setValue($request, self::$dbClass->products['product_1']['id']);
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $form = new class() extends CommentForm {};
        
        $service = new CommentsSaveService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $form);
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('commentform-text', $result);
    }
    
    /**
     * Тестирует метод CommentsSaveService::handle
     * если запрос AJAX
     */
    public function testHandleAjax()
    {
        $request = new class() extends Request {
            public $seocode;
            public $isAjax = true;
            public $text = 'text comment';
            public $name = 'John';
            public $email;
            public $id_product;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CommentForm'=>[
                        'text'=>$this->text,
                        'name'=>$this->name,
                        'email'=>$this->email,
                        'email'=>$this->email,
                        'id_product'=>$this->id_product,
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'id_product');
        $reflection->setValue($request, self::$dbClass->products['product_1']['id']);
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $form = new class() extends CommentForm {};
        
        $service = new CommentsSaveService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $form);
        
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
