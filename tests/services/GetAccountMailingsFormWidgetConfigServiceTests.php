<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountMailingsFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{MailingsFixture,
    UsersFixture};
use app\models\UsersModel;
use app\forms\MailingForm;

/**
 * Тестирует класс GetAccountMailingsFormWidgetConfigService
 */
class GetAccountMailingsFormWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetAccountMailingsFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountMailingsFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('accountMailingsFormWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAccountMailingsFormWidgetConfigService::handle
     * если пользователь не аутентифицирован
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testHandleIsGuest()
    {
        \Yii::$app->user->logout();
        
        $service = new GetAccountMailingsFormWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод  GetAccountMailingsFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new GetAccountMailingsFormWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(MailingForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
