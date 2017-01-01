<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\ModelSaver;
use yii\db\ActiveRecord;
use yii\base\Model;

/**
 * Тестирует класс ModelSaver
 */
class ModelSaverTests extends TestCase
{
    /**
     * Тестирует свойства ModelSaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModelSaver::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует свойства ModelSaver::setModel
     * усли передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $model = new class() {};
        
        $saver = new ModelSaver();
        $saver->setModel($model);
    }
    
    /**
     * Тестирует свойства ModelSaver::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $saver = new ModelSaver();
        $saver->setModel($model);
        
        $reflection = new \ReflectionProperty($saver, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует свойства ModelSaver::save
     * если пуст ModelSaver::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: model
     */
    public function testSaveEmptyModel()
    {
        $saver = new ModelSaver();
        $result = $saver->save();
    }
    
    /**
     * Тестирует свойства ModelSaver::save
     */
    public function testSave()
    {
        \Yii::$app->db->createCommand('CREATE TABLE {{test_saver}} (id INT, text VARCHAR(100))ENGINE=InnoDB')->execute();
        
        $model = new class() extends ActiveRecord {
            public static function tableName()
            {
                return 'test_saver';
            }
        };
        $model->setAttribute('id', 256);
        $model->setAttribute('text', 'some text');
        
        $saver = new ModelSaver();
        
        $reflection = new \ReflectionProperty($saver, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $model);
        
        $result = $saver->save();
        
        $this->assertTrue($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{test_saver}}')->queryAll();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        \Yii::$app->db->createCommand('DROP TABLE {{test_saver}}')->execute();
    }
}
