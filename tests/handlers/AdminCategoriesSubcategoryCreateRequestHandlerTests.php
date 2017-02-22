<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminCategoriesSubcategoryCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\forms\SubcategoryForm;

/**
 * Тестирует класс AdminCategoriesSubcategoryCreateRequestHandler
 */
class AdminCategoriesSubcategoryCreateRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminCategoriesSubcategoryCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCategoriesSubcategoryCreateRequestHandler::handle
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
                        'name'=>null,
                        'seocode'=>'new-name',
                        'id_category'=>2,
                        'active'=>true,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCategoriesSubcategoryCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $subcategory = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}}')->queryAll();
        $this->assertCount(2, $subcategory);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'SubcategoryForm'=>[
                        'name'=>'New name',
                        'seocode'=>'new-name',
                        'id_category'=>2,
                        'active'=>true,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $subcategory = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}}')->queryAll();
        $this->assertCount(3, $subcategory);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
