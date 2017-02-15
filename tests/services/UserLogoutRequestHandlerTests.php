<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\UserLogoutRequestHandler;
use yii\web\IdentityInterface;
use yii\helpers\Url;

/**
 * Тестирует класс UserLogoutRequestHandler
 */
class UserLogoutRequestHandlerTests extends TestCase
{
    private $handler;
    
    public static function setUpBeforeClass()
    {
        $model = new class() implements IdentityInterface {
            public $id = 17;
            public function getId()
            {
                return $this->id;
            }
            public static function findIdentity($id)
            {
                return $this;
            }
            public static function findIdentityByAccessToken($token, $type=null) {}
            public function getAuthKey(){}
            public function validateAuthKey($authKey){}
        };
        
        \Yii::$app->user->login($model);
    }
    
    public function setUp()
    {
        $this->handler = new UserLogoutRequestHandler();
    }
    
    /**
     * Тестирует метод UserLogoutRequestHandler::handle
     */
    public function testHandle()
    {
        $this->assertFalse(\Yii::$app->user->isGuest);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserLoginForm'=>[
                        'id'=>17
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertSame(Url::to(['/products-list/index']), $result);
        $this->assertTrue(\Yii::$app->user->isGuest);
    }
}
