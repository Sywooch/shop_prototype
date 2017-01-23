<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountSubscriptionsAddService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsMailingsFixture,
    UsersFixture};
use app\models\UsersModel;

/**
 * Тестирует класс AccountSubscriptionsAddService
 */
class AccountSubscriptionsAddServiceTests extends TestCase
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
     * Тестирует метод AccountSubscriptionsAddService::handle
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
        
        $service = new AccountSubscriptionsAddService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AccountSubscriptionsAddService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        \Yii::$app->db->createCommand('DELETE FROM {{emails_mailings}} WHERE [[id_mailing]]=:id_mailing')->bindValue(':id_mailing', 1)->execute();
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}} WHERE [[id_email]]=:id_email')->bindValue(':id_email', $user->id_email)->queryAll();
        $this->assertCount(1, $result);
        
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
        
        $service = new AccountSubscriptionsAddService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('unsubscribe', $result);
        $this->assertArrayHasKey('subscribe', $result);
        
        $this->assertNotEmpty($result['unsubscribe']);
        $this->assertNotEmpty($result['subscribe']);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}} WHERE [[id_email]]=:id_email')->bindValue(':id_email', $user->id_email)->queryAll();
        $this->assertCount(2, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
