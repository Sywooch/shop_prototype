<?php

namespace app\widgets;

use yii\helpers\Url;
use app\tests\{DbManager,
    MockModel};
use app\widgets\CategoriesMenuWidget;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\widgets\CategoriesMenuWidget
 */
class CategoriesMenuWidgetTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_categoriesName = 'Мужская одежда';
    private static $_subcategoryName = 'Рубашки';
    private static $_categoriesSeocode = 'menswear';
    private static $_subcategorySeocode = 'shirts';
    
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::widget()
     */
    public function testWidget()
    {
        $categoriesModel = new CategoriesModel(['id'=>self::$_id, 'name'=>self::$_categoriesName, 'seocode'=>self::$_categoriesSeocode]);
        
        $result = CategoriesMenuWidget::widget(['categoriesList'=>[$categoriesModel]]);
        
        $expectedUrl = '<ul class="categoriesMenu"><li><a href="' . Url::home() . 'products/' . self::$_categoriesSeocode . '">' . self::$_categoriesName . '</a><ul><li><a href="' . Url::home() . 'products/' . self::$_categoriesSeocode . '/' . self::$_subcategorySeocode . '">' . self::$_subcategoryName . '</a></li></ul></li></ul>';
        
        $this->assertEquals($expectedUrl, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
