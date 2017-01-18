<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\MailingsIndexService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    UsersFixture};
use app\controllers\MailingsController; 

/**
 * Тестирует класс MailingsIndexService
 */
class MailingsIndexServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'currency'=>CurrencyFixture::class,
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод MailingsIndexService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new MailingsController('mailings', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new MailingsIndexService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('mailingsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['mailingsWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
