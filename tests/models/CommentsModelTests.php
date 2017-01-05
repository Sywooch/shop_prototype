<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\{CommentsModel,
    NamesModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;

/**
 * Тестирует класс CommentsModel
 */
class CommentsModelTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CommentsModel
     */
    public function testProperties()
    {
        $model = new CommentsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('date', $model->attributes);
        $this->assertArrayHasKey('text', $model->attributes);
        $this->assertArrayHasKey('id_name', $model->attributes);
        $this->assertArrayHasKey('id_email', $model->attributes);
        $this->assertArrayHasKey('id_product', $model->attributes);
        $this->assertArrayHasKey('active', $model->attributes);
    }
    
    /**
     * Тестирует метод CommentsModel::tableName
     */
    public function testTableName()
    {
        $result = CommentsModel::tableName();
        
        $this->assertSame('comments', $result);
    }
    
    /**
     * Тестирует метод CommentsModel::getName
     */
    public function testGetColors()
    {
        $model = new CommentsModel();
        $model->id_name = 1;
        
        $result = $model->name;
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
