<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\UserLoginPostRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\controllers\ProductsListController;
use yii\helpers\Url;

/**
 * Тестирует класс UserLoginPostRequestHandler
 */
class UserLoginPostRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'emails'=>EmailsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        $this->handler = new UserLoginPostRequestHandler();
    }
    
    /**
     * Тестирует метод UserLoginPostRequestHandler::handle
     * если данные неверны
     */
    public function testHandleWrong()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public $isAjax = true;
            public $email;
            public function post()
            {
                return [
                    'UserLoginForm'=>[
                        'email'=>$this->email,
                        'password'=>'wrongpassword',
                    ],
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UserLoginPostRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->registry->clean();
        
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        \Yii::$app->db->createCommand('UPDATE {{users}} SET [[password]]=:password WHERE [[id]]=:id')->bindValues([':password'=>password_hash(self::$dbClass->users['user_1']['password'], PASSWORD_DEFAULT), ':id'=>self::$dbClass->users['user_1']['id']])->execute();
        
        $request = new class() {
            public $isAjax = true;
            public $email;
            public $password;
            public function post()
            {
                return [
                    'UserLoginForm'=>[
                        'email'=>$this->email,
                        'password'=>$this->password,
                    ],
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        $reflection = new \ReflectionProperty($request, 'password');
        $reflection->setValue($request, self::$dbClass->users['user_1']['password']);
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertSame(Url::to(['/products-list/index']), $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
