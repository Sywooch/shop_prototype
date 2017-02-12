<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountIndexRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    MailingsFixture,
    PurchasesFixture,
    UsersFixture};
use app\models\{CurrencyInterface,
    CurrencyModel,
    UsersModel};

/**
 * Тестирует класс AccountIndexRequestHandler
 */
class AccountIndexRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'orders'=>PurchasesFixture::class,
                'mailings'=>MailingsFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AccountIndexRequestHandler();
    }
    
    /**
     * Тестирует свойства AccountIndexRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountIndexRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountIndexRequestHandler::accountContactsWidgetConfig
     */
    public function testAccountContactsWidgetConfig()
    {
        $usersModel = new class() extends UsersModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountContactsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $usersModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('user', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(UsersModel::class, $result['user']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountIndexRequestHandler::accountCurrentOrdersWidgetConfig
     */
    public function testAccountCurrentOrdersWidgetConfig()
    {
        $purchasesArray = [new class() {}];
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountCurrentOrdersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesArray, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('purchases', $result);
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountIndexRequestHandler::accountMailingsWidgetConfig
     */
    public function testAccountMailingsWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('mailings', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountIndexRequestHandler::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {};
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('accountContactsWidgetConfig', $result);
        $this->assertArrayhasKey('accountCurrentOrdersWidgetConfig', $result);
        $this->assertArrayhasKey('accountMailingsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['accountContactsWidgetConfig']);
        $this->assertInternalType('array', $result['accountCurrentOrdersWidgetConfig']);
        $this->assertInternalType('array', $result['accountMailingsWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
