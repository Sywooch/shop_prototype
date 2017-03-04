<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminCategoriesCategoryChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\forms\CategoriesForm;

/**
 * Тестирует класс AdminCategoriesCategoryChangeRequestHandler
 */
class AdminCategoriesCategoryChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCategoriesCategoryChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCategoriesCategoryChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CategoriesForm'=>[
                        'id'=>null,
                        'active'=>1,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCategoriesCategoryChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $categories = \Yii::$app->db->createCommand('SELECT * FROM {{categories}} WHERE [[active]]=1')->queryAll();
        $this->assertCount(2, $categories);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CategoriesForm'=>[
                        'id'=>1,
                        'active'=>0,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertTrue($result);
        
        $categories = \Yii::$app->db->createCommand('SELECT * FROM {{categories}} WHERE [[active]]=1')->queryAll();
        $this->assertCount(1, $categories);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
