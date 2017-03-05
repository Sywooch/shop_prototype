<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminCategoriesSubcategoryChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\forms\SubcategoryForm;

/**
 * Тестирует класс AdminCategoriesSubcategoryChangeRequestHandler
 */
class AdminCategoriesSubcategoryChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCategoriesSubcategoryChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCategoriesSubcategoryChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'SubcategoryForm'=>[
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
     * Тестирует метод AdminCategoriesSubcategoryChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $subcategory = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}} WHERE [[active]]=1')->queryAll();
        $this->assertCount(2, $subcategory);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'SubcategoryForm'=>[
                        'id'=>1,
                        'active'=>0,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertTrue($result);
        
        $subcategory = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}} WHERE [[active]]=1')->queryAll();
        $this->assertCount(1, $subcategory);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
