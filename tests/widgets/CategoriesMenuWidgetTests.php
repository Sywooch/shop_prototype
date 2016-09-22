<?php

namespace app\widgets;

use yii\helpers\Url;
use app\tests\DbManager;
use app\tests\source\fixtures\{CategoriesFixture,
    ProductsFixture,
    SubcategoryFixture};
use app\widgets\CategoriesMenuWidget;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\widgets\CategoriesMenuWidget
 */
class CategoriesMenuWidgetTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::className(),
                'subcategory'=>SubcategoryFixture::className(),
                'products'=>ProductsFixture::className()
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::widget()
     */
    public function testWidget()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $categoriesModel = new CategoriesModel(['id'=>$fixture['id'], 'name'=>$fixture['name'], 'seocode'=>$fixture['seocode'], 'active'=>$fixture['active']]);
        
        $result = CategoriesMenuWidget::widget(['categoriesList'=>[$categoriesModel]]);
        
        $expectedUrl = '<ul class="categoriesMenu"><li><a href="' . Url::home() . $fixture['seocode'] . '">' . $fixture['name'] . '</a><ul><li><a href="' . Url::home() . $fixture['seocode'] . '/' . $fixtureSubcategory['seocode'] . '">' . $fixtureSubcategory['name'] . '</a></li></ul></li></ul>';
        
        $this->assertEquals($expectedUrl, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
