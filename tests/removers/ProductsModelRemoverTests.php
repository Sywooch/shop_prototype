<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\ProductsModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductsModelRemover
 */
class ProductsModelRemoverTests extends TestCase
{
    private $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new ProductsModelRemover();
    }
    
    /**
     * Тестирует свойства ProductsModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод ProductsModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends ProductsModel {};
        
        $this->remover->setModel($model);
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->remover);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод ProductsModelRemover::remove
     * если пуст ProductsModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод ProductsModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll();
        $this->assertCount(10, $result);
        
        $model = new class() {
            public $id = 1;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll();
        $this->assertCount(9, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
