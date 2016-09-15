<?php

namespace app\tests\widgets;

use yii\helpers\Url;
use app\tests\DbManager;
use app\tests\source\fixtures\{CategoriesFixture,
    ProductsFixture,
    SubcategoryFixture};
use app\widgets\BreadcrumbsWidget;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\widgets\BreadcrumbsWidget
 */
class BreadcrumbsWidgetTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::className(),
                'subcategory'=>SubcategoryFixture::className(),
                'products'=>ProductsFixture::className(),
            ]
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories
     */
    public function testWidgetForProductsList()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $_GET = ['category'=>$fixture['seocode']];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectUrl = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Главная') . '</a></li><li class="separator"> -> </li><li class="active">' . $fixture['name'] . '</li></ul>';
        
        $this->assertEquals($expectUrl, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories, subcategory
     */
    public function testWidgetForProductsListTwo()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectUrl = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Главная') . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . 'catalog/' . $fixture['seocode'] . '">' . $fixture['name'] . '</a></li><li class="separator"> -> </li><li class="active">' . $fixtureSubcategory['name'] . '</li></ul>';
        
        $this->assertEquals($expectUrl, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories, subcategory, id
     */
    public function testWidgetForProductsListThree()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        $fixtureProducts = self::$_dbClass->products['product_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode'], 'id'=>$fixtureProducts['id']];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectUrl = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Главная') . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . 'catalog/' . $fixture['seocode'] . '">' . $fixture['name'] . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . 'catalog/' . $fixture['seocode'] . '/' . $fixtureSubcategory['seocode'] . '">' . $fixtureSubcategory['name'] . '</a></li><li class="separator"> -> </li><li class="active">' . $fixtureProducts['name'] . '</li></ul>';
        
        $this->assertEquals($expectUrl, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
