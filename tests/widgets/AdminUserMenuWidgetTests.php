<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminUserMenuWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;

/**
 * Тестирует класс AdminUserMenuWidget
 */
class AdminUserMenuWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'orders'=>PurchasesFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод AdminUserMenuWidget::run
     */
    public function testRun()
    {
        $usersModel = new class() extends Model {
            public $id = 1;
            public $email;
            public function __construct()
            {
                $this->email = new class() {
                    public $email = 'mail@mail.com';
                };
            }
        };
        
        $widget = new AdminUserMenuWidget(['usersModel'=>$usersModel]);
        
        $widget->run();
        
        $this->assertTrue(true);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
