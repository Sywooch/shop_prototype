<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\EmailsMailingsArraySaver;
use yii\base\Model;
use app\tests\DbManager;
use app\tests\sources\fixtures\EmailsMailingsFixture;

/**
 * Тестирует класс EmailsMailingsArraySaver
 */
class EmailsMailingsArraySaverTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства EmailsMailingsArraySaver
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailsMailingsArraySaver::class);
        
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод EmailsMailingsArraySaver::setModels
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelsError()
    {
        $saver = new EmailsMailingsArraySaver();
        $saver->setModels('string');
    }
    
    /**
     * Тестирует метод EmailsMailingsArraySaver::setModels
     */
    public function testSetModels()
    {
        $model = new class() extends Model {};
        
        $saver = new EmailsMailingsArraySaver();
        $saver->setModels([$model]);
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($saver);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Model::class, $result[0]);
    }
    
    /**
     * Тестирует метод EmailsMailingsArraySaver::save
     * если пуст EmailsMailingsArraySaver::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testSaveEmptyModels()
    {
        $saver = new EmailsMailingsArraySaver();
        $saver->save();
    }
    
    /**
     * Тестирует метод EmailsMailingsArraySaver::save
     */
    public function testSave()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{emails_mailings}}')->execute();
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        
        $this->assertEmpty($result);
        
        $models = [
            new class() {
                public $id_email = 1;
                public $id_mailing = 2;
            },
            new class() {
                public $id_email = 2;
                public $id_mailing = 1;
            }
        ];
        
        $saver = new EmailsMailingsArraySaver();
        
        $reflection = new \ReflectionProperty($saver, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($saver, $models);
        
        $result = $saver->save();
        
        $this->assertEquals(2, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailings}}')->queryAll();
        $this->assertCount(2, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
