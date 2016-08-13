<?php

namespace app\tests\helpers;

use app\models\ProductsModel;
use app\helpers\CSVHelper;

/**
 * Тестирует класс app\helpers\CSVHelper
 */
class CSVHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_id2 = 2;
    private static $_name = 'some name';
    private static $_name2 = 'some name 2';
    private static $_short_description = 'short description';
    private static $_short_description2 = 'short description 2';
    private static $_price = 125.45;
    private static $_price2 = 58.10;
    private static $_active = 1;
    private static $_active2 = 0;
    
    private static $_path = '@app/tests/source/csv/';
    private static $_filename = 'test';
    private static $_fields = ['id', 'name', 'short_description', 'price', 'active'];
    
    /**
     * Тестирует метод CSVHelper::getCSV
     */
    public function testGetCSV()
    {
        $productsModel = new ProductsModel();
        $productsModel->id = self::$_id;
        $productsModel->name = self::$_name;
        $productsModel->short_description = self::$_short_description;
        $productsModel->price = self::$_price;
        $productsModel->active = self::$_active;
        
        $productsModel2 = new ProductsModel();
        $productsModel2->id = self::$_id2;
        $productsModel2->name = self::$_name2;
        $productsModel2->short_description = self::$_short_description2;
        $productsModel2->price = self::$_price2;
        $productsModel2->active = self::$_active2;
        
        $result = CSVHelper::getCSV([
            'path'=>\Yii::getAlias(self::$_path),
            'filename'=>self::$_filename,
            'objectsArray'=>[$productsModel, $productsModel2],
            'fields'=>self::$_fields
        ]);
        
        $this->assertTrue(is_string($result));
        $this->assertFalse(empty($result));
        $this->assertTrue((bool) strpos($result, '.csv'));
        
        $this->assertTrue(file_exists(\Yii::getAlias(self::$_path . self::$_filename . '.csv')));
    }
    
    public static function tearDownAfterClass()
    {
        if (file_exists(\Yii::getAlias(self::$_path . self::$_filename . '.csv'))) {
            unlink(\Yii::getAlias(self::$_path . self::$_filename . '.csv'));
        }
    }
}
