<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountMailingsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{MailingsFixture,
    UsersFixture};
use app\models\UsersModel;

/**
 * Тестирует класс GetAccountMailingsWidgetConfigService
 */
class GetAccountMailingsWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetAccountMailingsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountMailingsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('accountMailingsWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAccountMailingsWidgetConfigService::handle
     * если пользователь не аутентифицирован
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testHandleIsGuest()
    {
        \Yii::$app->user->logout();
        
        $service = new GetAccountMailingsWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод  GetAccountMailingsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new GetAccountMailingsWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
