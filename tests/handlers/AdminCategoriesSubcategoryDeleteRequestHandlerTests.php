<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminCategoriesSubcategoryDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;

/**
 * Тестирует класс AdminCategoriesSubcategoryDeleteRequestHandler
 */
class AdminCategoriesSubcategoryDeleteRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCategoriesSubcategoryDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCategoriesSubcategoryDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($category);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'SubcategoryForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('list', $result);
        
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($category);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
