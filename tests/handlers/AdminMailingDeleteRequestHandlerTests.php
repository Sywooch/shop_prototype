<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminMailingDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс AdminMailingDeleteRequestHandler
 */
class AdminMailingDeleteRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminMailingDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminMailingDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $delivery = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($delivery);
        
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
        
        $delivery = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($delivery);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
