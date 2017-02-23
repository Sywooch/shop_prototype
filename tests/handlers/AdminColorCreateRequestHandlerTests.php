<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminColorCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;
use app\forms\ColorsForm;

/**
 * Тестирует класс AdminColorCreateRequestHandler
 */
class AdminColorCreateRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminColorCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminColorCreateRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ColorsForm'=>[
                        'color'=>null,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminColorCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $colors = \Yii::$app->db->createCommand('SELECT * FROM {{colors}}')->queryAll();
        $this->assertCount(3, $colors);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ColorsForm'=>[
                        'color'=>'New color',
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $colors = \Yii::$app->db->createCommand('SELECT * FROM {{colors}}')->queryAll();
        $this->assertCount(4, $colors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
