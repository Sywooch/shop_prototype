<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminCategoriesCategoryDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;

/**
 * Тестирует класс AdminCategoriesCategoryDeleteRequestHandler
 */
class AdminCategoriesCategoryDeleteRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCategoriesCategoryDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCategoriesCategoryDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{categories}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($category);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'CategoriesForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('list', $result);
        $this->assertArrayHasKey('options', $result);
        
        $category = \Yii::$app->db->createCommand('SELECT * FROM {{categories}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertEmpty($category);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
