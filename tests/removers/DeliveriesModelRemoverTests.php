<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\DeliveriesModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\DeliveriesFixture;
use app\models\DeliveriesModel;

/**
 * Тестирует класс DeliveriesModelRemover
 */
class DeliveriesModelRemoverTests extends TestCase
{
    private $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new DeliveriesModelRemover();
    }
    
    /**
     * Тестирует свойства DeliveriesModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(DeliveriesModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод DeliveriesModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends DeliveriesModel {};
        
        $this->remover->setModel($model);
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->remover);
        
        $this->assertInstanceOf(DeliveriesModel::class, $result);
    }
    
    /**
     * Тестирует метод DeliveriesModelRemover::remove
     * если пуст DeliveriesModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод DeliveriesModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{deliveries}}')->queryAll();
        $this->assertCount(2, $result);
        
        $model = new class() {
            public $id = 1;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{deliveries}}')->queryAll();
        $this->assertCount(1, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
