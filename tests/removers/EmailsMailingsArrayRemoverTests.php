<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\EmailsMailingsArrayRemover;
use app\tests\DbManager;
use app\tests\sources\fixtures\EmailsMailingsFixture;

/**
 * Тестирует класс EmailsMailingsArrayRemover
 */
class EmailsMailingsArrayRemoverTests extends TestCase
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
     * Тестирует свойства EmailsMailingsArrayRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailsMailingsArrayRemover::class);
        
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод EmailsMailingsArrayRemover::setModels
     * если передан неверный параметр
     * @expectedException TypeError
     */
    public function testSetModelsError()
    {
        $remover = new EmailsMailingsArrayRemover();
        $remover->setModels('string');
    }
    
    /**
     * Тестирует метод EmailsMailingsArrayRemover::setModels
     */
    public function testSetModels()
    {
        $mock = new class() {};
        
        $remover = new EmailsMailingsArrayRemover();
        $remover->setModels([$mock]);
        
        $reflection = new \ReflectionProperty($remover, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($remover);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод EmailsMailingsArrayRemover::remove
     * если пуст EmailsMailingsArrayRemover::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testRemoveEmptyModels()
    {
        $remover = new EmailsMailingsArrayRemover();
        $remover->remove();
    }
    
    /**
     * Тестирует метод EmailsMailingsArrayRemover::remove
     */
    public function testRemove()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(4, $result);
        
        $models = [
            new class() {
                public $id_mailing = 1;
                public $id_email = 1;
            },
        ];
        
        $remover = new EmailsMailingsArrayRemover();
        
        $reflection = new \ReflectionProperty($remover, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($remover, $models);
        
        $result = $remover->remove();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(3, $result);
    }
    
    /**
     * Тестирует метод EmailsMailingsArrayRemover::remove
     * удаляю несколько записей
     */
    public function testRemoveSome()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(4, $result);
        
        $models = [
            new class() {
                public $id_mailing = 1;
                public $id_email = 1;
            },
            new class() {
                public $id_mailing = 2;
                public $id_email = 1;
            },
        ];
        
        $remover = new EmailsMailingsArrayRemover();
        
        $reflection = new \ReflectionProperty($remover, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($remover, $models);
        
        $result = $remover->remove();
        
        $this->assertEquals(2, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(2, $result);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
