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
     */
    public function testWidget()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $_GET = [\Yii::$app->params['categoryKey']=>$fixture['seocode']];
        
        \Yii::$app->params['breadcrumbs'] = [
            ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')],
            ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])], 'label'=>$fixture['name']],
        ];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectedString = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Home') . '</a></li><li class="separator"> -> </li><li><a href="../vendor/phpunit/phpunit/catalog">' . \Yii::t('base', 'All catalog') . '</a></li><li class="separator"> -> </li><li class="active">' . $fixture['name'] . '</li></ul>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     */
    public function testWidgetTwo()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        $fixtureProduct = self::$_dbClass->products['product_1'];
        
        $_GET = [\Yii::$app->params['categoryKey']=>$fixture['seocode'], \Yii::$app->params['subcategoryKey']=>$fixtureSubcategory['seocode'], \Yii::$app->params['productKey']=>$fixtureProduct['seocode']];
        
        \Yii::$app->params['breadcrumbs'] = [
            ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')],
            ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])], 'label'=>$fixture['name']],
            ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']), \Yii::$app->params['subcategoryKey']=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])], 'label'=>$fixtureSubcategory['name']],
            ['url'=>['/product-detail/index', \Yii::$app->params['productKey']=>\Yii::$app->request->get(\Yii::$app->params['productKey'])], 'label'=>$fixtureProduct['name']],
        ];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectedString = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . \Yii::t('base', 'Главная') . '</a></li><li class="separator"> -> </li><li><a href="../vendor/phpunit/phpunit/catalog">' . \Yii::t('base', 'All catalog') . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . $fixture['seocode'] . '">' . $fixture['name'] . '</a></li><li class="separator"> -> </li><li><a href="' . Url::home() . $fixture['seocode'] . '/' . $fixtureSubcategory['seocode'] . '">' . $fixtureSubcategory['name'] . '</a></li><li class="separator"> -> </li><li class="active">' . $fixtureProduct['name'] . '</li></ul>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
