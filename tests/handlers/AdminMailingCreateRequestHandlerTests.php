<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminMailingCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс AdminMailingCreateRequestHandler
 */
class AdminMailingCreateRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminMailingCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminMailingCreateRequestHandler::handle
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
                        'name'=>null,
                        'description'=>'New description',
                        'active'=>1,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminMailingCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $mailings = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}}')->queryAll();
        $this->assertCount(2, $mailings);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminMailingForm'=>[
                        'name'=>'New name',
                        'description'=>'New description',
                        'price'=>12,
                        'active'=>1,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $mailings = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}}')->queryAll();
        $this->assertCount(3, $mailings);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
