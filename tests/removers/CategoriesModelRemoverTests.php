<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\CategoriesModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\CategoriesModel;

/**
 * Тестирует класс СategoriesModelRemover
 */
class СategoriesModelRemoverTests extends TestCase
{
    private $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'сategories'=>CategoriesFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new CategoriesModelRemover();
    }
    
    /**
     * Тестирует свойства СategoriesModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод СategoriesModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends CategoriesModel {};
        
        $this->remover->setModel($model);
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->remover);
        
        $this->assertInstanceOf(CategoriesModel::class, $result);
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
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{categories}}')->queryAll();
        $this->assertCount(2, $result);
        
        $model = new class() {
            public $id = 1;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{categories}}')->queryAll();
        $this->assertCount(1, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
