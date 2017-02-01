<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountIndexService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture,
    UsersFixture};
use app\models\UsersModel;

/**
 * Тестирует класс AccountIndexService
 */
class AccountIndexServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'users'=>UsersFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод AccountIndexService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new AccountIndexService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('accountContactsWidgetConfig', $result);
        $this->assertArrayHasKey('accountCurrentOrdersWidgetConfig', $result);
        $this->assertArrayHasKey('accountMailingsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountContactsWidgetConfig']);
        $this->assertNotEmpty($result['accountContactsWidgetConfig']);
        
        $this->assertInternalType('array', $result['accountCurrentOrdersWidgetConfig']);
        $this->assertNotEmpty($result['accountCurrentOrdersWidgetConfig']);
        
        $this->assertInternalType('array', $result['accountMailingsWidgetConfig']);
        $this->assertNotEmpty($result['accountMailingsWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
