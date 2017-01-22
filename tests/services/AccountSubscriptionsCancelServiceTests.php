<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountSubscriptionsCancelService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsMailingsFixture,
    UsersFixture};
use app\models\UsersModel;

/**
 * Тестирует класс AccountSubscriptionsCancelService
 */
class AccountSubscriptionsCancelServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод AccountSubscriptionsCancelService::handle
     * если запрос с ошибками
     */
    public function testHandleErrors()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'MailingForm'=>[
                        'id'=>null,
                    ]
                ];
            }
        };
        
        $service = new AccountSubscriptionsCancelService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AccountSubscriptionsCancelService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}} WHERE [[id_email]]=:id_email')->bindValue(':id_email', $user->id_email)->queryAll();
        $this->assertCount(2, $result);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'MailingForm'=>[
                        'id'=>1,
                    ]
                ];
            }
        };
        
        $service = new AccountSubscriptionsCancelService();
        $result = $service->handle($request);
        
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
