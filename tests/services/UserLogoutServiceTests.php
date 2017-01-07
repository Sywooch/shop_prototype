<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserLogoutService;
use yii\web\{IdentityInterface,
    Request};
use yii\helpers\Url;

/**
 * Тестирует класс UserLogoutService
 */
class UserLogoutServiceTests extends TestCase
{
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
    
    /**
     * Тестирует метод UserLogoutService::handle
     * если пуст UserLogoutService::request
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: request
     */
    public function testHandleEmptyRequest()
    {
        $this->assertFalse(\Yii::$app->user->isGuest);
        
        $request = new class() extends Request {};
        
        $service = new UserLogoutService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод UserLogoutService::handle
     */
    public function testHandle()
    {
        $this->assertFalse(\Yii::$app->user->isGuest);
        
        $request = new class() extends Request {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserLoginForm'=>[
                        'id'=>17
                    ]
                ];
            }
        };
        
        $service = new UserLogoutService();
        $result = $service->handle($request);
        
        $this->assertSame(Url::to(['/products-list/index']), $result);
        
        $this->assertTrue(\Yii::$app->user->isGuest);
    }
}
