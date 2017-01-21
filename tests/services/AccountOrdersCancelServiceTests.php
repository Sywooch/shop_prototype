<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountOrdersCancelService;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;

/**
 * Тестирует класс AccountOrdersCancelService
 */
class AccountOrdersCancelServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод AccountOrdersCancelService::handle
     * если ошибки валидации
     */
    public function testHandleErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'id'=>null,
                    ],
                ];
            }
        };
        
        $service = new AccountOrdersCancelService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AccountOrdersCancelService::handle
     */
    public function testHandle()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertFalse((bool) $result['canceled']);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'id'=>1,
                    ],
                ];
            }
        };
        
        $service = new AccountOrdersCancelService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertEquals(\Yii::t('base', 'Canceled'), $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertTrue((bool) $result['canceled']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
