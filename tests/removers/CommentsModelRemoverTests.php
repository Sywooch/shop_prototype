<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\removers\CommentsModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\models\CommentsModel;

/**
 * Тестирует класс CommentsModelRemover
 */
class CommentsModelRemoverTests extends TestCase
{
    public $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new CommentsModelRemover();
    }
    
    /**
     * Тестирует свойства CommentsModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод CommentsModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $this->remover->setModel($model);
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->remover);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод CommentsModelRemover::remove
     * если пуст CommentsModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод CommentsModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{comments}}')->queryAll();
        $this->assertCount(4, $result);
        
        $model = new class() {
            public $id = 2;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{comments}}')->queryAll();
        $this->assertCount(3, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
