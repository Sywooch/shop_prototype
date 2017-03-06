<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminMailingFormRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminMailingFormRequestHandler
 */
class AdminMailingFormRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminMailingFormRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminMailingFormRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminMailingFormRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminMailingFormRequestHandler::adminMailingFormWidgetConfig
     */
    public function testAdminMailingFormWidgetConfig()
    {
        $mailingsModel = new class() extends Model {};
        $mailingForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminMailingFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsModel, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailing', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(Model::class, $result['mailing']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminMailingFormRequestHandler::handle
     * если пуста форма
     * @expectedException ErrorException
     */
    public function testHandleEmptyForm()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminMailingForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $reqult = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminMailingFormRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminMailingForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
