<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminMailingChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminMailingChangeRequestHandler
 */
class AdminMailingChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminMailingChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminMailingChangeRequestHandler::adminMailingDataWidgetConfig
     */
    public function testAdminMailingDataWidgetConfig()
    {
        $mailingsModel = new class() extends Model {};
        $mailingForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminMailingDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsModel, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertInstanceOf(Model::class, $result['mailing']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminMailingChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminMailingForm'=>[
                        'id'=>null,
                        'name'=>'Name',
                        'description'=>'Description',
                        'active'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminMailingChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $oldMailing = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldMailing);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminMailingForm'=>[
                        'id'=>1,
                        'name'=>'New name',
                        'description'=>'New description',
                        'active'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $newMailing = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newMailing);
        
        $this->assertEquals($oldMailing['id'], $newMailing['id']);
        $this->assertNotEquals($oldMailing['name'], $newMailing['name']);
        $this->assertNotEquals($oldMailing['description'], $newMailing['description']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
