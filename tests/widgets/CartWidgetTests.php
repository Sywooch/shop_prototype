<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartWidget;
use app\repositories\RepositoryInterface;

class CartWidgetTests extends TestCase
{
    private static $repository;
    private static $currency;
    
    public static function setUpBeforeClass()
    {
        self::$repository = new class () implements RepositoryInterface {
            public function getGroup($request)
            {
                return new class () {
                    public $quantity = 2;
                    public $price = 1678.12;
                };
            }
            public function getOne($request)
            {
                
            }
            public function getCriteria()
            {
                
            }
            public function addCriteria($query)
            {
                
            }
        };
        
        self::$currency = new class () implements RepositoryInterface {
            public function getGroup($request)
            {
                
            }
            public function getOne($request)
            {
                return new class () {
                    public $exchange_rate = 27.26;
                    public $code = 'USD';
                };
            }
            public function getCriteria()
            {
                
            }
            public function addCriteria($query)
            {
                
            }
        };
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     * вызываю с пустым CartWidget::repository
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetErrorRepository()
    {
        $result = CartWidget::widget();
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     * вызываю с пустым CartWidget::currency
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetErrorCurrency()
    {
        $result = CartWidget::widget([
            'repository'=>self::$repository,
        ]);
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     * вызываю с пустым CartWidget::view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetErrorView()
    {
        $result = CartWidget::widget([
            'repository'=>self::$repository,
            'currency'=>self::$currency,
        ]);
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     */
    public function testWidget()
    {
        $result = CartWidget::widget([
            'repository'=>self::$repository,
            'currency'=>self::$currency,
            'view'=>'short-cart.twig'
        ]);
        
        print_r($result);
        
        $this->assertEquals(1, preg_match('/<div id="cart">/', $result));
        $this->assertEquals(1, preg_match('/<p>/', $result));
    }
}
