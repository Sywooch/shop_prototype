<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\removers\ProductsColorsModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsColorsFixture;
use app\models\ProductsColorsModel;

/**
 * Тестирует класс ProductsColorsModelRemover
 */
class ProductsColorsModelRemoverTests extends TestCase
{
    public $dbClass;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'products_colors'=>ProductsColorsFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsColorsModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsColorsModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод ProductsColorsModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $remover = new ProductsColorsModelRemover();
        $remover->setModel($model);
        
        $reflection = new \ReflectionProperty($remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($remover);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод ProductsColorsModelRemover::remove
     * если пуст ProductsColorsModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $remover = new ProductsColorsModelRemover();
        $remover->remove();
    }
    
    /**
     * Тестирует метод ProductsColorsModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll();
        $this->assertCount(11, $result);
        
        $model = new class() extends Model {
            public $id_product = 2;
        };
        
        $remover = new ProductsColorsModelRemover();
        
        $reflection = new \ReflectionProperty($remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($remover, $model);
        
        $result = $remover->remove();
        
        $this->assertEquals(2, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll();
        $this->assertCount(9, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
