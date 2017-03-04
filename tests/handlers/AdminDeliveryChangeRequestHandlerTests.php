<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminDeliveryChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    DeliveriesFixture};
use app\forms\AbstractBaseForm;
use app\models\{CurrencyInterface,
    CurrencyModel};

/**
 * Тестирует класс AdminDeliveryChangeRequestHandler
 */
class AdminDeliveryChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class,
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminDeliveryChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminDeliveryChangeRequestHandler::adminDeliveryDataWidgetConfig
     */
    public function testAdminDeliveryDataWidgetConfig()
    {
        $deliveriesModel = new class() extends Model {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $deliveryForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminDeliveryDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $deliveriesModel, $currentCurrencyModel, $deliveryForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertInstanceOf(Model::class, $result['delivery']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminDeliveryChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'DeliveriesForm'=>[
                        'id'=>null,
                        'name'=>'Name',
                        'description'=>'Description',
                        'price'=>23,
                        'active'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminDeliveryChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $oldDelivery = \Yii::$app->db->createCommand('SELECT * FROM {{deliveries}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldDelivery);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'DeliveriesForm'=>[
                        'id'=>1,
                        'name'=>'New name',
                        'description'=>'New description',
                        'price'=>23,
                        'active'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $newDelivery = \Yii::$app->db->createCommand('SELECT * FROM {{deliveries}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newDelivery);
        
        $this->assertEquals($oldDelivery['id'], $newDelivery['id']);
        $this->assertNotEquals($oldDelivery['name'], $newDelivery['name']);
        $this->assertNotEquals($oldDelivery['description'], $newDelivery['description']);
        $this->assertNotEquals($oldDelivery['price'], $newDelivery['price']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
