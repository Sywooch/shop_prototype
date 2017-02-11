<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountSubscriptionsHandlerTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    UsersFixture};
use app\forms\MailingForm;

/**
 * Тестирует класс AccountSubscriptionsHandlerTrait
 */
class AccountSubscriptionsHandlerTraitTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
                'users'=>UsersFixture::class,
                'emails'=>EmailsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new class() {
            use AccountSubscriptionsHandlerTrait;
        };
    }
    
    /**
     * Тестирует метод AccountSubscriptionsHandlerTrait::unsubscribe
     * если запрос с ошибками
     */
    public function testUnsubscribe()
    {
        $reflection = new \ReflectionMethod($this->handler, 'unsubscribe');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, self::$dbClass->emails['email_1']['email']);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(MailingForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountSubscriptionsHandlerTrait::subscribe
     * если запрос с ошибками
     */
    public function testSubscribe()
    {
        $reflection = new \ReflectionMethod($this->handler, 'subscribe');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, self::$dbClass->emails['email_1']['email']);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(MailingForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
