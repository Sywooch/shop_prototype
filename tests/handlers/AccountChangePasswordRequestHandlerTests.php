<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountChangePasswordRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс AccountChangePasswordRequestHandler
 */
class AccountChangePasswordRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
        
        $this->handler = new AccountChangePasswordRequestHandler();
    }
    
    /**
     * Тестирует свойства AccountChangePasswordRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangePasswordRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangePasswordRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $result = $this->handler->handle(new class() {});
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('accountChangePasswordWidgetConfig', $result);
        $this->assertInternalType('array', $result['accountChangePasswordWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
