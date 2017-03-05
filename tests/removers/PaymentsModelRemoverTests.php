<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\PaymentsModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\models\PaymentsModel;

/**
 * Тестирует класс PaymentsModelRemover
 */
class PaymentsModelRemoverTests extends TestCase
{
    private $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new PaymentsModelRemover();
    }
    
    /**
     * Тестирует свойства PaymentsModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaymentsModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод PaymentsModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends PaymentsModel {};
        
        $this->remover->setModel($model);
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->remover);
        
        $this->assertInstanceOf(PaymentsModel::class, $result);
    }
    
    /**
     * Тестирует метод PaymentsModelRemover::remove
     * если пуст PaymentsModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод PaymentsModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{payments}}')->queryAll();
        $this->assertCount(2, $result);
        
        $model = new class() {
            public $id = 1;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{payments}}')->queryAll();
        $this->assertCount(1, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
