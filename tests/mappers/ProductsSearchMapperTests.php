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
        'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images','categories', 'subcategory'],
        'orderByField'=>'date',
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
    
    /**
     * Тестирую утверждение, что при передаче в $_GET типа сортировки, значение свойства ProductsSearchMapper::orderByType изменяется
     */
    public function testOrderByType()
    {
        $productsMapper = new ProductsSearchMapper(self::$_config);
        
        \Yii::$app->filters->sortingType = 'ASC';
        
        $productsMapper = new ProductsSearchMapper(self::$_config);
        
        $this->assertEquals('ASC', $productsMapper->orderByType);
    }
    
    /**
     * Тестирую утверждение, что при передаче в $_GET поля сортировки, значение свойства ProductsSearchMapper::orderByField изменяется
     */
    public function testOrderByField()
    {
        $productsMapper = new ProductsSearchMapper(self::$_config);
        
        $this->assertEquals('date', $productsMapper->orderByField);
        
        \Yii::$app->filters->sortingField = 'price';
        
        $productsMapper = new ProductsSearchMapper(self::$_config);
        
        $this->assertEquals('price', $productsMapper->orderByField);
    }
}
