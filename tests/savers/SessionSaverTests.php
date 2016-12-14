<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\SessionSaver;
use yii\base\Model;

/**
 * Тестирует класс SessionSaver
 */
class SessionSaverTests extends TestCase
{
    /**
     * Тестирует свойства SessionSaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SessionSaver::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод SessionSaver::rules
     * если не указаны SessionSaver::key, SessionSaver::models
     */
    public function testRulesRequired()
    {
        $saver = new SessionSaver();
        $saver->validate();
        
        $this->assertNotEmpty($saver->errors);
        $this->assertCount(2, $saver->errors);
        $this->assertArrayHasKey('key', $saver->errors);
        $this->assertArrayHasKey('models', $saver->errors);
    }
    
    /**
     * Тестирует метод SessionSaver::rules
     * если SessionSaver::models содержит неверные типы
     */
    public function testRulesModelsNotObject()
    {
        $saver = new SessionSaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver,'test_key');
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, [1]);
        
        $saver->validate();
        
        $this->assertNotEmpty($saver->errors);
        $this->assertCount(1, $saver->errors);
        $this->assertArrayHasKey('models', $saver->errors);
        
        $model = new class() extends Model {};
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, [$model]);
        
        $saver->validate();
        
        $this->assertEmpty($saver->errors);
    }
    
    /**
     * Тестирует метод SessionSaver::setModels
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelsError()
    {
        $saver = new SessionSaver();
        $saver->setModels('a');
    }
    
    /**
     * Тестирует метод SessionSaver::setModels
     */
    public function testSetModels()
    {
        $model = new class() extends Model {};
        
        $saver = new SessionSaver();
        $saver->setModels([$model]);
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Model::class, $result[0]);
    }
    
    /**
     * Тестирует метод SessionSaver::getModels
     */
    public function testGetModels()
    {
        $model = new class() extends Model {};
        
        $saver = new SessionSaver();
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($saver, [$model]);
        
        $result = $saver->getModels();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Model::class, $result[0]);
    }
    
    /**
     * Тестирует метод SessionSaver::save
     * если количество элементов > 1
     */
    public function testSave()
    {
        $model_1 = new class() extends Model {
            public $id = 1;
        };
        
        $model_2 = new class() extends Model {
            public $id = 2;
        };
        
        $saver = new SessionSaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key_test');
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, [$model_1, $model_2]);
        
        $saver->save();
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get('key_test');
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        foreach ($result as $item) {
            $this->assertInternalType('array', $item);
        }
        
        $session->remove('key_test');
        $session->close();
    }
    
    /**
     * Тестирует метод SessionSaver::save
     * если количество элементов === 1
     */
    public function testSaveOne()
    {
        $model_1 = new class() extends Model {
            public $id = 1;
        };
        
        $saver = new SessionSaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key_test');
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, [$model_1]);
        
        $saver->save();
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get('key_test');
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('id', $result);
        
        $session->remove('key_test');
        $session->close();
    }
}
