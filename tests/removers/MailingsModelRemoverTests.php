<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\removers\MailingsModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс MailingsModelRemover
 */
class MailingsModelRemoverTests extends TestCase
{
    private $dbClass;
    private $remover;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->remover = new MailingsModelRemover();
    }
    
    /**
     * Тестирует свойства MailingsModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод MailingsModelRemover::setModel
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
     * Тестирует метод MailingsModelRemover::remove
     * если пуст MailingsModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод MailingsModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}}')->queryAll();
        $this->assertCount(2, $result);
        
        $model = new class() {
            public $id = 1;
        };
        
        $reflection = new \ReflectionProperty($this->remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, $model);
        
        $result = $this->remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{mailings}}')->queryAll();
        $this->assertCount(1, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
