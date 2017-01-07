<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\SessionArraySaver;
use yii\base\Model;

/**
 * Тестирует класс SessionArraySaver
 */
class SessionArraySaverTests extends TestCase
{
    /**
     * Тестирует свойства SessionArraySaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SessionArraySaver::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('models'));
        $this->assertTrue($reflection->hasProperty('flash'));
    }
    
    /**
     * Тестирует метод SessionArraySaver::setModels
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelsError()
    {
        $saver = new SessionArraySaver();
        $saver->setModels('string');
    }
    
    /**
     * Тестирует метод SessionArraySaver::setModels
     */
    public function testSetModels()
    {
        $model = new class() extends Model {};
        
        $saver = new SessionArraySaver();
        $saver->setModels([$model]);
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Model::class, $result[0]);
    }
    
    /**
     * Тестирует метод SessionArraySaver::save
     * если пуст SessionArraySaver::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testSaveEmptyKey()
    {
        $saver = new SessionArraySaver();
        $saver->save();
    }
    
    /**
     * Тестирует метод SessionArraySaver::save
     * если пуст SessionArraySaver::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testSaveEmptyModels()
    {
        $saver = new SessionArraySaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key_test');
        
        $saver->save();
    }
    
    /**
     * Тестирует метод SessionArraySaver::save
     */
    public function testSave()
    {
        $model_1 = new class() extends Model {
            public $id = 1;
        };
        
        $model_2 = new class() extends Model {
            public $id = 2;
        };
        
        $saver = new SessionArraySaver();
        
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
     * Тестирует метод SessionArraySaver::save
     * сохраняю flash
     */
    public function testSaveFlash()
    {
        $model_1 = new class() extends Model {
            public $id = 1;
        };
        
        $model_2 = new class() extends Model {
            public $id = 2;
        };
        
        $saver = new SessionArraySaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key_test_flash');
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, [$model_1, $model_2]);
        
        $reflection = new \ReflectionProperty($saver, 'flash');
        $reflection->setValue($saver, true);
        
        $saver->save();
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->getFlash('key_test_flash', null, true);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        foreach ($result as $item) {
            $this->assertInternalType('array', $item);
        }
        
        $result = $session->hasFlash('key_test_flash');
        
        $this->assertFalse($result);
        
        $session->close();
    }
}
