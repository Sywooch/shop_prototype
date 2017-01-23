<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountChangeDataService;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс AccountChangeDataService
 */
class AccountChangeDataServiceTests extends TestCase
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
     * Тестирует свойства AccountChangeDataService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeDataService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangeDataService::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new AccountChangeDataService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('accountChangeDataWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountChangeDataWidgetConfig']);
        $this->assertNotEmpty($result['accountChangeDataWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
