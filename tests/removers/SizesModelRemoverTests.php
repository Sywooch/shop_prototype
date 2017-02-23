<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\SizesModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\SizesFixture;
use app\models\SizesModel;

/**
 * Тестирует класс СategoriesModelRemover
 */
class SizesModelRemoverTests extends TestCase
{
    private $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new SizesModelRemover();
    }
    
    /**
     * Тестирует свойства СategoriesModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод СategoriesModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends SizesModel {};
        
        $this->remover->setModel($model);
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->remover);
        
        $this->assertInstanceOf(SizesModel::class, $result);
    }
    
    /**
     * Тестирует метод СategoriesModelRemover::remove
     * если пуст СategoriesModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод СategoriesModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{sizes}}')->queryAll();
        $this->assertCount(3, $result);
        
        $model = new class() {
            public $id = 1;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{sizes}}')->queryAll();
        $this->assertCount(2, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
