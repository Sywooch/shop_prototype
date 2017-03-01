<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\{CommentsModel,
    EmailsModel,
    NamesModel,
    ProductsModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\{CommentsFixture,
    EmailsFixture,
    ProductsFixture};

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
                'emails'=>EmailsFixture::class,
                'products'=>ProductsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CommentsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
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
     * Тестирует метод CommentsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new CommentsModel(['scenario'=>CommentsModel::SAVE]);
        $model->attributes = [
            'date'=>time(), 
            'text'=>'text', 
            'id_name'=>1, 
            'id_email'=>1, 
            'id_product'=>1
        ];
        
        $this->assertEquals(time(), $model->date);
        $this->assertEquals('text', $model->text);
        $this->assertEquals(1, $model->id_name);
        $this->assertEquals(1, $model->id_email);
        $this->assertEquals(1, $model->id_product);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::DELETE]);
        $model->attributes = [
            'id'=>15
        ];
        
        $this->assertEquals(15, $model->id);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::EDIT]);
        $model->attributes = [
            'id'=>2, 
            'text'=>'text', 
        ];
        
        $this->assertEquals(2, $model->id);
        $this->assertEquals('text', $model->text);
    }
    
    /**
     * Тестирует метод CommentsModel::rules
     */
    public function testRules()
    {
        $model = new CommentsModel(['scenario'=>CommentsModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(5, $model->errors);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::SAVE]);
        $model->attributes = [
            'date'=>time(), 
            'text'=>'text', 
            'id_name'=>1, 
            'id_email'=>1, 
            'id_product'=>1
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->active);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::DELETE]);
        $model->attributes = [
            'id'=>15
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::EDIT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        
        $model = new CommentsModel(['scenario'=>CommentsModel::EDIT]);
        $model->attributes = [
            'id'=>2, 
            'text'=>'text', 
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->active);
    }
    
    /**
     * Тестирует метод CommentsModel::getName
     */
    public function testGetName()
    {
        $model = new CommentsModel();
        $model->id_name = 1;
        
        $result = $model->name;
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    /**
     * Тестирует метод CommentsModel::getEmail
     */
    public function testGetEmail()
    {
        $model = new CommentsModel();
        $model->id_email = 1;
        
        $result = $model->email;
        
        $this->assertInstanceOf(EmailsModel::class, $result);
    }
    
    /**
     * Тестирует метод CommentsModel::getProduct
     */
    public function testGetProduct()
    {
        $model = new CommentsModel();
        $model->id_product = 1;
        
        $result = $model->product;
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
