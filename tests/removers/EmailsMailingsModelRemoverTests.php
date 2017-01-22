<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\EmailsMailingsModelRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\EmailsMailingsFixture;
use app\models\EmailsMailingsModel;

/**
 * Тестирует класс EmailsMailingsModelRemover
 */
class EmailsMailingsModelRemoverTests extends TestCase
{
    public $dbClass;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства EmailsMailingsModelRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailsMailingsModelRemover::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод EmailsMailingsModelRemover::setModel
     * если передан неверный параметр
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $model = new class() {};
        
        $remover = new EmailsMailingsModelRemover();
        $remover->setModel($model);
    }
    
    /**
     * Тестирует метод EmailsMailingsModelRemover::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends EmailsMailingsModel {};
        
        $remover = new EmailsMailingsModelRemover();
        $remover->setModel($model);
        
        $reflection = new \ReflectionProperty($remover, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($remover);
        
        $this->assertInstanceOf(EmailsMailingsModel::class, $result);
    }
    
    /**
     * Тестирует метод EmailsMailingsModelRemover::remove
     * если пуст EmailsMailingsModelRemover::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $remover = new EmailsMailingsModelRemover();
        $remover->remove();
    }
    
    /**
     * Тестирует метод EmailsMailingsModelRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(4, $result);
        
        $model = new class() {
            public $id_mailing = 1;
            public $id_email = 1;
        };
        
        $remover = new EmailsMailingsModelRemover();
        
        $reflection = new \ReflectionProperty($remover, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($remover, $model);
        
        $result = $remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(3, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
