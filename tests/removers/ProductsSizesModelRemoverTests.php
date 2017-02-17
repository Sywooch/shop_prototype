<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\removers\ProductsSizesModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsSizesFixture;
use app\models\ProductsSizesModel;

/**
 * Тестирует класс ProductsSizesModelRemover
 */
class ProductsSizesModelRemoverTests extends TestCase
{
    public $dbClass;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'products_sizes'=>ProductsSizesFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsSizesModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsSizesModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод ProductsSizesModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $remover = new ProductsSizesModelRemover();
        $remover->setModel($model);
        
        $reflection = new \ReflectionProperty($remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($remover);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод ProductsSizesModelRemover::remove
     * если пуст ProductsSizesModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $remover = new ProductsSizesModelRemover();
        $remover->remove();
    }
    
    /**
     * Тестирует метод ProductsSizesModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll();
        $this->assertCount(11, $result);
        
        $model = new class() extends Model {
            public $id_product = 2;
        };
        
        $remover = new ProductsSizesModelRemover();
        
        $reflection = new \ReflectionProperty($remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($remover, $model);
        
        $result = $remover->remove();
        
        $this->assertEquals(2, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll();
        $this->assertCount(9, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
