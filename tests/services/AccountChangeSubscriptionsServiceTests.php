<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountChangeSubscriptionsService;
use app\models\UsersModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{MailingsFixture,
    UsersFixture};

/**
 * Тестирует класс AccountChangeSubscriptionsService
 */
class AccountChangeSubscriptionsServiceTests extends TestCase
{
    private static $dbClass;
    
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
    }
    
    /**
     * Тестирует свойства AccountChangeSubscriptionsService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeSubscriptionsService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangeSubscriptionsService::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new AccountChangeSubscriptionsService();
        $result = $service->handle();
        
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
