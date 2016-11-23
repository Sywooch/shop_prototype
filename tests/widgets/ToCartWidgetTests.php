<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\ToCartWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\{ProductsModel,
    PurchasesModel};

/**
 * Тестирует класс app\widgets\ToCartWidget
 */
class ToCartWidgetTests extends TestCase
{
    private static $dbClass;
    private $repository;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ToCartWidget::widget
     * вызываю с пустым $model
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetModelEmpty()
    {
        $result = ToCartWidget::widget([]);
    }
    
    /**
     * Тестирует метод ToCartWidget::setModel
     * передаю не наследующий Model объект
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $result = new ToCartWidget([
            'model'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод ToCartWidget::widget
     * вызываю с пустым $purchase
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetPurchaseEmpty()
    {
        $result = ToCartWidget::widget([
            'model'=>new class() extends Model {},
        ]);
    }
    
    /**
     * Тестирует метод ToCartWidget::setPurchase
     * передаю не наследующий Model объект
     * @expectedException TypeError
     */
    public function testSetPurchaseError()
    {
        $result = new ToCartWidget([
            'model'=>new class() extends Model {},
            'purchase'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод ToCartWidget::widget
     * вызываю с пустым $view
     * @expectedException yii\base\ErrorException
     */
    public function testSetViewEmpty()
    {
        $result = ToCartWidget::widget([
            'model'=>new class() extends Model {},
        ]);
    }
    
    /**
     * Тестирует метод ToCartWidget::widget
     */
    public function testWidget()
    {
        $result = ToCartWidget::widget([
            'model'=>ProductsModel::find()->where(['[[id]]'=>1])->one(),
            'purchase'=>new PurchasesModel(['quantity'=>1]),
            'view'=>'add-to-cart-form.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<form id="add-to-cart-form"/', $result));
        $this->assertEquals(1, preg_match('/<input type="number" id="purchasesmodel-quantity"/', $result));
        $this->assertEquals(1, preg_match('/<select id="purchasesmodel-id_color"/', $result));
        $this->assertEquals(1, preg_match('/<select id="purchasesmodel-id_size"/', $result));
        $this->assertEquals(1, preg_match('/<input type="hidden" id="purchasesmodel-id_product"/', $result));
        $this->assertEquals(1, preg_match('/<input type="hidden" id="purchasesmodel-price"/', $result));
        $this->assertEquals(1, preg_match('/<input type="submit" value="' . \Yii::t('base', 'Add to cart') . '">/', $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
