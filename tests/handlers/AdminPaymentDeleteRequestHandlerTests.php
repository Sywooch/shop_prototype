<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminPaymentDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;

/**
 * Тестирует класс AdminPaymentDeleteRequestHandler
 */
class AdminPaymentDeleteRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminPaymentDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminPaymentDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $delivery = \Yii::$app->db->createCommand('SELECT * FROM {{payments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($delivery);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'PaymentsForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $delivery = \Yii::$app->db->createCommand('SELECT * FROM {{payments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($delivery);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
