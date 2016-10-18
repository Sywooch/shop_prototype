<?php

namespace app\tests\widgets;

use yii\helpers\Url;
use app\tests\DbManager;
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
                'categories'=>'app\tests\sources\fixtures\CategoriesFixture',
                'subcategory'=>'app\tests\sources\fixtures\SubcategoryFixture',
                'products'=>'app\tests\sources\fixtures\ProductsFixture',
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
        $fixtureProduct = self::$_dbClass->products['product_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $_GET = ['category'=>$fixture['seocode']];
        
        \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectedString = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Home') . '</a></li><li class="separator"> -> </li><li><a href="../vendor/phpunit/phpunit/catalog">' . \Yii::t('base', 'All catalog') . '</a></li><li class="separator"> -> </li><li class="active">' . $fixture['name'] . '</li></ul>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories, subcategory
     */
    public function testWidgetForProductsListTwo()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureProduct = self::$_dbClass->products['product_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        
        \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectedString = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Главная') . '</a></li><li class="separator"> -> </li><li><a href="../vendor/phpunit/phpunit/catalog">' . \Yii::t('base', 'All catalog') . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . $fixture['seocode'] . '">' . $fixture['name'] . '</a></li><li class="separator"> -> </li><li class="active">' . $fixtureSubcategory['name'] . '</li></ul>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories, subcategory, id
     */
    public function testWidgetForProductsListThree()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureProduct = self::$_dbClass->products['product_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode'], 'product'=>$fixtureProduct['seocode']];
        
        \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectedString = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Главная') . '</a></li><li class="separator"> -> </li><li><a href="../vendor/phpunit/phpunit/catalog">' . \Yii::t('base', 'All catalog') . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . $fixture['seocode'] . '">' . $fixture['name'] . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . $fixture['seocode'] . '/' . $fixtureSubcategory['seocode'] . '">' . $fixtureSubcategory['name'] . '</a></li><li class="separator"> -> </li><li class="active">' . $fixtureProduct['name'] . '</li></ul>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для результатов поиска
     */
    public function testWidgetForSearch()
    {
        $_GET = [];
        
        \Yii::$app->params['breadcrumbs'] = ['label'=>\Yii::t('base', 'Searching results')];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectedString = '<ul class="breadcrumb"><li><a href="../vendor/phpunit/phpunit/">Главная</a></li><li class="separator"> -> </li><li class="active">' . \Yii::t('base', 'Searching results') . '</li></ul>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
