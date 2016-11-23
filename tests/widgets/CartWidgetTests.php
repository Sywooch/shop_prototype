<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartWidget;
use app\repositories\RepositoryInterface;

/**
 * Тестирует класс app\widgets\CartWidget
 */
class CartWidgetTests extends TestCase
{
    private static $repository;
    private static $repositoryEmpty;
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
            public function saveGroup($key)
            {
            }
        };
        
        self::$repositoryEmpty = new class () implements RepositoryInterface {
            public function getGroup($request)
            {
                return new class () {
                    public $quantity = 0;
                    public $price = 0;
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
            public function saveGroup($key)
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
            public function saveGroup($key)
            {
            }
        };
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     * вызываю с пустым CartWidget::repository
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetEmptyRepository()
    {
        $result = CartWidget::widget([]);
    }
    
    /**
     * Тестирует метод CartWidget::setRepository()
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $result = new CartWidget([
            'repository'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     * вызываю с пустым CartWidget::currency
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetEmptyCurrency()
    {
        $result = CartWidget::widget([
            'repository'=>self::$repository,
        ]);
    }
    
    /**
     * Тестирует метод CartWidget::setCurrency()
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $result = new CartWidget([
            'repository'=>self::$repository,
            'currency'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     * вызываю с пустым CartWidget::view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetEmptyView()
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
        
        $this->assertEquals(1, preg_match('/<div id="cart">/', $result));
        $this->assertEquals(1, preg_match('/<p>' . \Yii::t('base', 'Products in cart: {goods}, Total cost: {cost}', ['goods'=>2, 'cost'=>\Yii::$app->formatter->asDecimal(1678.12 * 27.26, 2)]) . ' USD/', $result));
        $this->assertEquals(1, preg_match('/<a href=".*">' . \Yii::t('base', 'To cart') . '<\/a>/', $result));
        $this->assertEquals(1, preg_match('/<form id="clean-cart-form"/', $result));
        $this->assertEquals(1, preg_match('/<input type="submit" value="' . \Yii::t('base', 'Clean') . '">/', $result));
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     * корзина пуста
     */
    public function testWidgetEmpty()
    {
        $result = CartWidget::widget([
            'repository'=>self::$repositoryEmpty,
            'currency'=>self::$currency,
            'view'=>'short-cart.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<div id="cart">/', $result));
        $this->assertEquals(1, preg_match('/<p>' . \Yii::t('base', 'Products in cart: {goods}, Total cost: {cost}', ['goods'=>0, 'cost'=>\Yii::$app->formatter->asDecimal(0, 2)]) . ' USD/', $result));
    }
}
