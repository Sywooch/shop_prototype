<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminOrderDetailChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\{CurrencyInterface,
    CurrencyModel,
    PurchasesModel};
use app\forms\{AbstractBaseForm,
    AdminChangeOrderForm};

/**
 * Тестирует класс AdminOrderDetailChangeRequestHandler
 */
class AdminOrderDetailChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminOrderDetailChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeRequestHandler::adminOrderDataWidgetConfig
     */
    public function testAdminOrderDataWidgetConfig()
    {
        $purchasesModel = new class() extends PurchasesModel {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $adminChangeOrderForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminOrderDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesModel, $currentCurrencyModel, $adminChangeOrderForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertInstanceOf(PurchasesModel::class, $result['purchase']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $oldOrder = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldOrder);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>1,
                        'name'=>'Name',
                        'surname'=>'Surname',
                        'phone'=>'908484',
                        'address'=>'Address',
                        'city'=>'City',
                        'country'=>'Country',
                        'postcode'=>'UIYT67',
                        'status'=>'shipped',
                        'quantity'=>13,
                        'id_color'=>2,
                        'id_size'=>2,
                        'id_delivery'=>2,
                        'id_payment'=>2
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $newOrder = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newOrder);
        
        $this->assertEquals($oldOrder['id'], $newOrder['id']);
        $this->assertNotEquals($oldOrder['quantity'], $newOrder['quantity']);
        $this->assertNotEquals($oldOrder['id_color'], $newOrder['id_color']);
        $this->assertNotEquals($oldOrder['id_size'], $newOrder['id_size']);
        $this->assertNotEquals($oldOrder['id_delivery'], $newOrder['id_delivery']);
        $this->assertNotEquals($oldOrder['id_payment'], $newOrder['id_payment']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
