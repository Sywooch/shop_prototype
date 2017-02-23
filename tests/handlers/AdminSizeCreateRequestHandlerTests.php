<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminSizeCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\SizesFixture;
use app\forms\SizesForm;

/**
 * Тестирует класс AdminSizeCreateRequestHandler
 */
class AdminSizeCreateRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminSizeCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminSizeCreateRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'SizesForm'=>[
                        'size'=>null,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminSizeCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $sizes = \Yii::$app->db->createCommand('SELECT * FROM {{sizes}}')->queryAll();
        $this->assertCount(3, $sizes);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'SizesForm'=>[
                        'size'=>105,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $sizes = \Yii::$app->db->createCommand('SELECT * FROM {{sizes}}')->queryAll();
        $this->assertCount(4, $sizes);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
