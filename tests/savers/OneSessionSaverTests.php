<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\OneSessionSaver;
use yii\base\Model;

/**
 * Тестирует класс OneSessionSaver
 */
class OneSessionSaverTests extends TestCase
{
    /**
     * Тестирует свойства
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OneSessionSaver::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод OneSessionSaver::rules
     */
    public function testRules()
    {
        $saver = new OneSessionSaver();
        $saver->attributes = [];
        $saver->validate();
        
        $this->assertNotEmpty($saver->errors);
        $this->assertCount(2, $saver->errors);
        $this->assertArrayHasKey('key', $saver->errors);
        $this->assertArrayHasKey('model', $saver->errors);
        
        $saver = new OneSessionSaver();
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'key');
        
        $reflection = new \ReflectionProperty($saver, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, 'model');
        
        $saver->validate();
        
        $this->assertEmpty($saver->errors);
    }
    
    /**
     * Тестирует метод OneSessionSaver::setModel
     * если передаю неверный тип
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $model = new class() {};
        
        $saver = new OneSessionSaver();
        $saver->setModel($model);
    }
    
    /**
     * Тестирует метод OneSessionSaver::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $saver = new OneSessionSaver();
        $saver->setModel($model);
    }
    
    /**
     * Тестирует метод OneSessionSaver::save
     * если отсутствуют необходимые данные
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Key».
     * @expectedExceptionMessage Необходимо заполнить «Model».
     */
    public function testSaveError()
    {
        $saver = new OneSessionSaver();
        $saver->save();
    }
    
    /**
     * Тестирует метод OneSessionSaver::save
     */
    public function testSave()
    {
        $model = new class() extends Model {
            public $test = 'test';
        };
        
        $saver = new OneSessionSaver();
        
        $reflection = new \ReflectionProperty($saver, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $model);
        
        $reflection = new \ReflectionProperty($saver, 'key');
        $reflection->setValue($saver, 'OneSessionSaver');
        
        $result = $saver->save();
        
        $this->assertTrue($result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get('OneSessionSaver');
        $session->close();
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertSame(['test'=>'test'], $result);
    }
    
    public static function tearDownAfterClass()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->remove('OneSessionSaver');
        $session->close();
    }
}
