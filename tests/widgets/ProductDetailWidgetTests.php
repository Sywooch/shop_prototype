<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\tests\DbManager;
use app\widgets\ProductDetailWidget;
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    ProductsFixture,
    RelatedProductsFixture};
use app\models\ProductsModel;

class ProductDetailWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'currency'=>CurrencyFixture::class,
                'products'=>ProductsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'related_products'=>RelatedProductsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setModel
     * вызываю с пустым $model
     * @expectedException yii\base\ErrorException
     */
    public function testSetModelEmpty()
    {
        $result = ProductDetailWidget::widget([]);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setModel
     * передаю не наследующий Model объект
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $result = new ProductDetailWidget([
            'model'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::widget
     * вызываю с пустым $view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetViewEmpty()
    {
        $result = new ProductDetailWidget([
            'model'=>new class() extends Model {},
        ]);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::widget
     */
    public function testWidget()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['currencyKey'], self::$dbClass->currency['currency_1']);
        $session->close();
        
        $result = ProductDetailWidget::widget([
            'model'=>ProductsModel::find()->where(['[[id]]'=>1])->one(),
            'view'=>'product-detail.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<div class="user-info">/', $result));
        $this->assertEquals(1, preg_match('/<div id="cart">/', $result));
        $this->assertEquals(1, preg_match('/<p><strong>' . \Yii::t('base', 'Currency:') . '<\/strong><\/p>/', $result));
        $this->assertEquals(1, preg_match('/<form id="set-currency-form"/', $result));
        $this->assertEquals(1, preg_match('/<div class="search-form">/', $result));
        $this->assertEquals(1, preg_match('/<ul class="categories-menu">/', $result));
        $this->assertEquals(1, preg_match('/<ul class="breadcrumb">/', $result));
        $this->assertEquals(1, preg_match('/<h1>.+<\/h1>/', $result));
        $this->assertEquals(1, preg_match('/<div class="images">/', $result));
        $this->assertEquals(1, preg_match('/<img src=".+" alt="">/', $result));
        $this->assertEquals(1, preg_match('/<p><strong>' . \Yii::t('base', 'Colors:') . '<\/strong><\/p>/', $result));
        $this->assertEquals(1, preg_match('/<p><strong>' . \Yii::t('base', 'Sizes:') . '<\/strong><\/p>/', $result));
        $this->assertEquals(1, preg_match('/<p><strong>' . \Yii::t('base', 'Price:') . '<\/strong>/', $result));
        $this->assertEquals(1, preg_match('/<p><strong>' . \Yii::t('base', 'Code:') . '<\/strong>/', $result));
        $this->assertEquals(1, preg_match('/<form id="add-to-cart-form"/', $result));
        $this->assertEquals(1, preg_match('/<p><strong>' . \Yii::t('base', 'Similar products:') . '<\/strong><\/p>/', $result));
        $this->assertEquals(1, preg_match('/<p><strong>' . \Yii::t('base', 'Related products:') . '<\/strong><\/p>/', $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
