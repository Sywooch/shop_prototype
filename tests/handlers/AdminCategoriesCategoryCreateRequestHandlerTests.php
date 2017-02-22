<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminCategoriesCategoryCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\forms\CategoriesForm;

/**
 * Тестирует класс AdminCategoriesCategoryCreateRequestHandler
 */
class AdminCategoriesCategoryCreateRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminCategoriesCategoryCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCategoriesCategoryCreateRequestHandler::handle
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
                        'name'=>null,
                        'seocode'=>'new-name',
                        'active'=>true,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCategoriesCategoryCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $categories = \Yii::$app->db->createCommand('SELECT * FROM {{categories}}')->queryAll();
        $this->assertCount(2, $categories);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CategoriesForm'=>[
                        'name'=>'New name',
                        'seocode'=>'new-name',
                        'active'=>true,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $categories = \Yii::$app->db->createCommand('SELECT * FROM {{categories}}')->queryAll();
        $this->assertCount(3, $categories);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
