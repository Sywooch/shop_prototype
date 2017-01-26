<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountMailingsUnsubscribeWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{MailingsFixture,
    UsersFixture};
use app\models\UsersModel;
use app\forms\MailingForm;

/**
 * Тестирует класс GetAccountMailingsUnsubscribeWidgetConfigService
 */
class GetAccountMailingsUnsubscribeWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
                'users'=>UsersFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAccountMailingsUnsubscribeWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountMailingsUnsubscribeWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('accountMailingsUnsubscribeWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAccountMailingsUnsubscribeWidgetConfigService::handle
     * если пользователь не аутентифицирован
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testHandleIsGuest()
    {
        \Yii::$app->user->logout();
        
        $service = new GetAccountMailingsUnsubscribeWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод  GetAccountMailingsUnsubscribeWidgetConfigService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new GetAccountMailingsUnsubscribeWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(MailingForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
