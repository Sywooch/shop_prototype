<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountChangePasswordService;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс AccountChangePasswordService
 */
class AccountChangePasswordServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
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
     * Тестирует свойства AccountChangePasswordService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangePasswordService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangePasswordService::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new AccountChangePasswordService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('accountChangePasswordWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountChangePasswordWidgetConfig']);
        $this->assertNotEmpty($result['accountChangePasswordWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
