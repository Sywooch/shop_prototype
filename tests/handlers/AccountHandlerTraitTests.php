<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountHandlerTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    UsersFixture};
use app\forms\MailingForm;

/**
 * Тестирует класс AccountHandlerTrait
 */
class AccountHandlerTraitTests extends TestCase
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
            use AccountHandlerTrait;
        };
    }
    
    /**
     * Тестирует метод AccountHandlerTrait::accountMailingsUnsubscribeWidgetConfig
     * если запрос с ошибками
     */
    public function testAccountMailingsUnsubscribeWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsUnsubscribeWidgetConfig');
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
     * Тестирует метод AccountHandlerTrait::accountMailingsFormWidgetConfig
     * если запрос с ошибками
     */
    public function testAccountMailingsFormWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsFormWidgetConfig');
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
