<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminMailingsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminMailingsRequestHandler
 */
class AdminMailingsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminMailingsRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminMailingsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminMailingsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminMailingsRequestHandler::adminCreateMailingWidgetConfig
     */
    public function testAdminCreateMailingWidgetConfig()
    {
        $mailingsForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateMailingWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminMailingsRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminMailingsWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateMailingWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminMailingsWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateMailingWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
