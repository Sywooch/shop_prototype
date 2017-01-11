<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserLoginPostService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\controllers\ProductsListController;
use yii\helpers\Url;

/**
 * Тестирует класс UserLoginPostService
 */
class UserLoginPostServiceTests extends TestCase
{
    private static $dbClass;
    
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
    
    /**
     * Тестирует метод UserLoginPostService::handle
     * если AJAX
     */
    public function testHandleAjax()
    {
        \Yii::$app->registry->clean();
        
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public $isAjax = true;
            public $email;
            public $password;
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
        
        $service = new UserLoginPostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UserLoginPostService::handle
     * если POST
     */
    public function testHandlePost()
    {
        \Yii::$app->registry->clean();
        
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        \Yii::$app->db->createCommand('UPDATE {{users}} SET [[password]]=:password WHERE [[id]]=:id')->bindValues([':password'=>password_hash(self::$dbClass->users['user_1']['password'], PASSWORD_DEFAULT), ':id'=>self::$dbClass->users['user_1']['id']])->execute();
        
        $request = new class() {
            public $isPost = true;
            public $isAjax = false;
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
        
        $service = new UserLoginPostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertSame(Url::to(['/products-list/index']), $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        \Yii::$app->registry->clean();
    }
}
