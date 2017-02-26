<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\removers\RelatedProductsModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\RelatedProductsFixture;
use app\models\RelatedProductsModel;

/**
 * Тестирует класс RelatedProductsModelRemover
 */
class RelatedProductsModelRemoverTests extends TestCase
{
    private $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'related_products'=>RelatedProductsFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new RelatedProductsModelRemover();
    }
    
    /**
     * Тестирует свойства RelatedProductsModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RelatedProductsModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод RelatedProductsModelRemover::setModel
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
     * Тестирует метод RelatedProductsModelRemover::remove
     * если пуст RelatedProductsModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод RelatedProductsModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}}')->queryAll();
        $this->assertCount(5, $result);
        
        $model = new class() extends Model {
            public $id_product = 2;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{related_products}}')->queryAll();
        $this->assertCount(4, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
