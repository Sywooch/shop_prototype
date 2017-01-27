<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PurchasesIdUserFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\models\PurchasesModel;

/**
 * Тестирует класс PurchasesIdUserFinder
 */
class PurchasesIdUserFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PurchasesIdUserFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchasesIdUserFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_user'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PurchasesIdUserFinder::setId_user
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetId_userError()
    {
        $id_user = null;
        
        $widget = new PurchasesIdUserFinder();
        $widget->setId_user($id_user);
    }
    
    /**
     * Тестирует метод PurchasesIdUserFinder::setId_user
     */
    public function testSetId_user()
    {
        $id_user = 2;
        
        $widget = new PurchasesIdUserFinder();
        $widget->setId_user($id_user);
        
        $reflection = new \ReflectionProperty($widget, 'id_user');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод PurchasesIdUserFinder::find
     * если пуст PurchasesIdUserFinder::id_user
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_user
     */
    public function testFindEmptyIdUser()
    {
        $finder = new PurchasesIdUserFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод PurchasesIdUserFinder::find
     */
    public function testFind()
    {
        $finder = new PurchasesIdUserFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_user');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->purchases['purchase_1']['id_user']);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PurchasesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
