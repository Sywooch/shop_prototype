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
    private static $_id = 1;
    private static $_productName = 'Ботинки Черный Пионер';
    private static $_categoriesSeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_categoriesName = 'Одежда';
    private static $_subcategoryName = 'Пиджаки';
    private static $_main = 'products';
    private static $_mainName = 'Главная';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_categoriesName, ':seocode'=>self::$_categoriesSeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_subcategoryName, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_productName, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories
     */
    public function testWidgetForProductsList()
    {
        $_GET = ['categories'=>self::$_categoriesSeocode];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectUrl = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . self::$_mainName . '</a>&nbsp;->&nbsp;</li><li class="active">' . self::$_categoriesName . '</li></ul>';
        
        $this->assertEquals($expectUrl, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories, subcategory
     */
    public function testWidgetForProductsListTwo()
    {
        $_GET = ['categories'=>self::$_categoriesSeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectUrl = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . self::$_mainName . '</a>&nbsp;->&nbsp;</li><li><a href="' . Url::home() . self::$_main . '/' . self::$_categoriesSeocode . '">' . self::$_categoriesName . '</a>&nbsp;->&nbsp;</li><li class="active">' . self::$_subcategoryName . '</li></ul>';
        
        $this->assertEquals($expectUrl, $result);
    }
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     * для списка продуктов
     * при налиции в $_GET categories, subcategory, id
     */
    public function testWidgetForProductsListThree()
    {
        $_GET = ['categories'=>self::$_categoriesSeocode, 'subcategory'=>self::$_subcategorySeocode, 'id'=>self::$_id];
        
        $result = BreadcrumbsWidget::widget();
        
        $expectUrl = '<ul class="breadcrumb"><li><a href="' . Url::home() . '">' . self::$_mainName . '</a>&nbsp;->&nbsp;</li><li><a href="' . Url::home() . self::$_main . '/' . self::$_categoriesSeocode . '">' . self::$_categoriesName . '</a>&nbsp;->&nbsp;</li><li><a href="' . Url::home() . self::$_main . '/' . self::$_categoriesSeocode . '/' . self::$_subcategorySeocode . '">' . self::$_subcategoryName . '</a>&nbsp;->&nbsp;</li><li class="active">' . self::$_productName . '</li></ul>';
        
        $this->assertEquals($expectUrl, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
