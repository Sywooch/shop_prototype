<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\SessionModelSaver;
use yii\base\Model;

/**
 * Тестирует класс SessionModelSaver
 */
class SessionModelSaverTests extends TestCase
{
    /**
     * Тестирует свойства SessionModelSaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SessionModelSaver::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('model'));
        $this->assertTrue($reflection->hasProperty('flash'));
    }
    
    /**
     * Тестирует метод SessionModelSaver::setModel
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $model = new class() {};
        
        $saver = new SessionModelSaver();
        $saver->setModel($model);
    }
    
    /**
     * Тестирует метод SessionModelSaver::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $saver = new SessionModelSaver();
        $saver->setModel($model);
        
        $reflection = new \ReflectionProperty($saver, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод SessionModelSaver::save
     * если пуст SessionModelSaver::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: key
     */
    public function testSaveEmptyKey()
    {
        $saver = new SessionModelSaver();
        $saver->save();
    }
    
    /**
     * Тестирует метод SessionModelSaver::save
     * если пуст SessionModelSaver::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: model
     */
    public function testSaveEmptyModels()
    {
        $saver = new SessionModelSaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key_test');
        
        $saver->save();
    }
    
    /**
     * Тестирует метод SessionModelSaver::save
     */
    public function testSave()
    {
        $model = new class() extends Model {
            public $id = 1;
        };
        
        $saver = new SessionModelSaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key_test');
        
        $reflection = new \ReflectionProperty($saver, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $model);
        
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
    
    /**
     * Тестирует метод SessionModelSaver::save
     * и сохраняю flash
     */
    public function testSaveFlash()
    {
        $model = new class() extends Model {
            public $id = 1;
        };
        
        $saver = new SessionModelSaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key_test_flash');
        
        $reflection = new \ReflectionProperty($saver, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $model);
        
        $reflection = new \ReflectionProperty($saver, 'flash');
        $reflection->setValue($saver, true);
        
        $saver->save();
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->getFlash('key_test_flash', null, true);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('id', $result);
        
        $result = $session->hasFlash('key_test_flash');
        
        $this->assertFalse($result);
        
        $session->close();
    }
}
