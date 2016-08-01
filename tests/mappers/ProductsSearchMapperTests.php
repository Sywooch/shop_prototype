<?php

namespace app\tests\mappers;

use app\mappers\ProductsSearchMapper;
use app\models\ProductsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ProductsSearchMapper
 * !!!ВАЖНО Поскольку индекс sphynx получает данные из рабочей БД, для успешного прохождения теста убедитесь, 
 * что БД содержит запись, удовлетворяющую условиям поиска из self::$_search
 */
class ProductsSearchMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_config = [
        'tableName'=>'shop',
        'fields'=>['id'],
    ];
    private static $_search = 'усиленный мыс';
    
    public static function setUpBeforeClass()
    {
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ProductsSearchMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = [\Yii::$app->params['searchKey']=>self::$_search];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[], 'sizes'=>[], 'brands'=>[]]);
        
        $productsMapper = new ProductsSearchMapper(self::$_config);
        $productsList = $productsMapper->getGroup();
        
        $this->assertTrue(is_array($productsList));
        $this->assertFalse(empty($productsList));
        $this->assertTrue(is_object($productsList[0]));
        $this->assertTrue($productsList[0] instanceof ProductsModel);
    }
}
