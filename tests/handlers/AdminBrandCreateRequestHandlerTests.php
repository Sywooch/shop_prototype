<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminBrandCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;
use app\forms\BrandsForm;

/**
 * Тестирует класс AdminBrandCreateRequestHandler
 */
class AdminBrandCreateRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminBrandCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminBrandCreateRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'BrandsForm'=>[
                        'brand'=>null,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminBrandCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $brands = \Yii::$app->db->createCommand('SELECT * FROM {{brands}}')->queryAll();
        $this->assertCount(2, $brands);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'BrandsForm'=>[
                        'brand'=>'New brand',
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $brands = \Yii::$app->db->createCommand('SELECT * FROM {{brands}}')->queryAll();
        $this->assertCount(3, $brands);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
