<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountChangePasswordRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;
use app\forms\AbstractBaseForm;

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
     * Тестирует метод AccountChangePasswordRequestHandler::accountChangePasswordWidgetConfig
     */
    public function testAccountChangePasswordWidgetConfig()
    {
        $userChangePasswordForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountChangePasswordWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $userChangePasswordForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
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
