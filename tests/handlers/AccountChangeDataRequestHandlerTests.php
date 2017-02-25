<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountChangeDataRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс AccountChangeDataRequestHandler
 */
class AccountChangeDataRequestHandlerTests extends TestCase
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
        
        $this->handler = new AccountChangeDataRequestHandler();
    }
    
    /**
     * Тестирует свойства AccountChangeDataRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeDataRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountChangeDataRequestHandler::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {};
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('accountChangeDataWidgetConfig', $result);
        $this->assertInternalType('array', $result['accountChangeDataWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
