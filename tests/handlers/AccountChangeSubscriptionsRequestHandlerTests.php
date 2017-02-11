<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountChangeSubscriptionsRequestHandler;
use app\models\UsersModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{MailingsFixture,
    UsersFixture};

/**
 * Тестирует класс AccountChangeSubscriptionsRequestHandler
 */
class AccountChangeSubscriptionsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'mailings'=>MailingsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AccountChangeSubscriptionsRequestHandler();
    }
    
    /**
     * Тестирует свойства AccountChangeSubscriptionsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeSubscriptionsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangeSubscriptionsRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $result = $this->handler->handle(new class() {});
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('accountMailingsUnsubscribeWidgetConfig', $result);
        $this->assertArrayHasKey('accountMailingsFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountMailingsUnsubscribeWidgetConfig']);
        $this->assertNotEmpty($result['accountMailingsUnsubscribeWidgetConfig']);
        
        $this->assertInternalType('array', $result['accountMailingsFormWidgetConfig']);
        $this->assertNotEmpty($result['accountMailingsFormWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
