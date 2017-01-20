<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountContactsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс GetAccountContactsWidgetConfigService
 */
class GetAccountContactsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAccountContactsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountContactsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('accountContactsWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAccountContactsWidgetConfigService::handle
     * если пользователь не аутентифицирован
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testHandleIsGuest()
    {
        \Yii::$app->user->logout();
        
        $service = new GetAccountContactsWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод  GetAccountContactsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new GetAccountContactsWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInstanceOf(UsersModel::class, $result['user']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
