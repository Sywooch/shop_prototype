<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminUserSubscriptionsCancelRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    UsersFixture};
use app\models\UsersModel;

/**
 * Тестирует класс AdminUserSubscriptionsCancelRequestHandler
 */
class AdminUserSubscriptionsCancelRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
                'users'=>UsersFixture::class,
                'emails'=>EmailsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminUserSubscriptionsCancelRequestHandler();
    }
    
    /**
     * Тестирует метод AdminUserSubscriptionsCancelRequestHandler::handle
     * если запрос с ошибками
     */
    public function testHandleErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserMailingForm'=>[
                        'id_user'=>1,
                        'id'=>null,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminUserSubscriptionsCancelRequestHandler::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}} WHERE [[id_email]]=:id_email')->bindValue(':id_email', $user->id_email)->queryAll();
        $this->assertCount(2, $result);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserMailingForm'=>[
                        'id_user'=>1,
                        'id'=>1,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('unsubscribe', $result);
        $this->assertArrayHasKey('subscribe', $result);
        
        $this->assertNotEmpty($result['unsubscribe']);
        $this->assertNotEmpty($result['subscribe']);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}} WHERE [[id_email]]=:id_email')->bindValue(':id_email', $user->id_email)->queryAll();
        $this->assertCount(1, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
