<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\SurnameGetSaveSurnameService;
use app\tests\DbManager;
use app\tests\sources\fixtures\SurnamesFixture;
use app\models\SurnamesModel;

/**
 * Тестирует класс SurnameGetSaveSurnameService
 */
class SurnameGetSaveSurnameServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'surnames'=>SurnamesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SurnameGetSaveSurnameService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SurnameGetSaveSurnameService::class);
        
        $this->assertTrue($reflection->hasProperty('surnamesModel'));
        $this->assertTrue($reflection->hasProperty('surname'));
    }
    
    /**
     * Тестирует метод SurnameGetSaveSurnameService::getSurname
     */
    public function testGetSurname()
    {
        $service = new SurnameGetSaveSurnameService();
        
        $reflection = new \ReflectionProperty($service, 'surname');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->surnames['surname_1']['surname']);
        
        $reflection = new \ReflectionMethod($service, 'getSurname');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(SurnamesModel::class, $result);
    }
    
    /**
     * Тестирует метод SurnameGetSaveSurnameService::handle
     * если пуст SurnameGetSaveSurnameService::surname
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: surname
     */
    public function testHandleEmptyName()
    {
        $request = [];
        
        $service = new SurnameGetSaveSurnameService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод SurnameGetSaveSurnameService::handle
     * если surname уже в СУБД
     */
    public function testHandleExistsSurname()
    {
        $request = ['surname'=>self::$dbClass->surnames['surname_1']['surname']];
        
        $service = new SurnameGetSaveSurnameService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(SurnamesModel::class, $result);
    }
    
    /**
     * Тестирует метод SurnameGetSaveSurnameService::handle
     * если surname еще не в СУБД
     */
    public function testHandleNotExistsSurname()
    {
        $request = ['surname'=>'Shakespeare'];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{surnames}} WHERE [[surname]]=:surname')->bindValue(':surname', 'Shakespeare')->queryOne();
        
        $this->assertEmpty($result);
        
        $service = new SurnameGetSaveSurnameService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(SurnamesModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{surnames}} WHERE [[surname]]=:surname')->bindValue(':surname', 'Shakespeare')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('Shakespeare', $result['surname']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
