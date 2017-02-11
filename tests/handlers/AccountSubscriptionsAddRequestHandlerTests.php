<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountSubscriptionsAddRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    UsersFixture};
use app\models\UsersModel;
use app\forms\MailingForm;

/**
 * Тестирует класс AccountSubscriptionsAddRequestHandler
 */
class AccountSubscriptionsAddRequestHandlerTests extends TestCase
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
        
        $this->handler = new AccountSubscriptionsAddRequestHandler();
    }
    
    /**
     * Тестирует метод AccountSubscriptionsAddRequestHandler::handle
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
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AccountSubscriptionsAddRequestHandler::handle
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
        
        $result = $this->handler->handle($request);
        
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
